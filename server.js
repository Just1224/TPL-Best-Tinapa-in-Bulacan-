require('dotenv').config();
const express = require('express');
const session = require('express-session');
const pgSession = require('connect-pg-simple')(session);
const bcrypt = require('bcrypt');
const path = require('path');
const db = require('./db');

const app = express();
const PORT = process.env.PORT || 10000;

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));
app.use(express.static(path.join(__dirname, 'public')));
app.use(express.urlencoded({ extended: true }));

app.use(session({
  store: new pgSession({ pool: db.pool }),
  secret: process.env.SESSION_SECRET || 'tinapa-secret',
  resave: false,
  saveUninitialized: false,
  cookie: { maxAge: 24 * 60 * 60 * 1000 },
}));

function isAuth(req, res, next) {
  if (req.session.userId) return next();
  res.redirect('/login');
}

function isAdmin(req, res, next) {
  if (req.session.adminId) return next();
  res.redirect('/admin/login');
}

app.get('/', async (req, res) => {
  const servicesRes = await db.query('SELECT * FROM services ORDER BY created_at DESC');
  res.render('index', { services: servicesRes.rows, user: req.session.userName });
});

app.get('/register', (req, res) => res.render('register', { message: null }));
app.post('/register', async (req, res) => {
  const { name, email, password } = req.body;
  if (!name || !email || !password) return res.render('register', { message: 'Fill in all fields' });
  const userExist = await db.query('SELECT 1 FROM users WHERE email = $1', [email]);
  if (userExist.rowCount > 0) return res.render('register', { message: 'Email already exists' });
  const hashed = await bcrypt.hash(password, 10);
  await db.query('INSERT INTO users(name,email,password) VALUES($1,$2,$3)', [name, email, hashed]);
  res.redirect('/login');
});

app.get('/login', (req, res) => res.render('login', { message: null }));
app.post('/login', async (req, res) => {
  const { email, password } = req.body;
  const userRes = await db.query('SELECT * FROM users WHERE email = $1', [email]);
  if (userRes.rowCount === 0) return res.render('login', { message: 'Invalid credentials' });
  const user = userRes.rows[0];
  const match = await bcrypt.compare(password, user.password);
  if (!match) return res.render('login', { message: 'Invalid credentials' });
  req.session.userId = user.id;
  req.session.userName = user.name;
  res.redirect('/');
});

app.get('/logout', (req, res) => {
  req.session.destroy(() => res.redirect('/'));
});

app.get('/cart', isAuth, async (req, res) => {
  const cartRes = await db.query('SELECT c.*, s.title, s.price, s.image FROM cart c JOIN services s ON c.service_id=s.id WHERE c.user_id=$1', [req.session.userId]);
  res.render('cart', { cart: cartRes.rows, user: req.session.userName, message: null });
});

app.post('/cart/add', isAuth, async (req, res) => {
  const { service_id, quantity } = req.body;
  const q = parseInt(quantity, 10) || 1;
  const exists = await db.query('SELECT * FROM cart WHERE user_id=$1 AND service_id=$2', [req.session.userId, service_id]);
  if (exists.rowCount > 0) {
    await db.query('UPDATE cart SET quantity = quantity + $1 WHERE user_id=$2 AND service_id=$3', [q, req.session.userId, service_id]);
  } else {
    await db.query('INSERT INTO cart(user_id,service_id,quantity) VALUES($1,$2,$3)', [req.session.userId, service_id, q]);
  }
  res.redirect('/cart');
});

app.post('/cart/update', isAuth, async (req, res) => {
  const { cart_id, quantity } = req.body;
  const q = parseInt(quantity, 10) || 1;
  if (q <= 0) {
    await db.query('DELETE FROM cart WHERE id=$1 AND user_id=$2', [cart_id, req.session.userId]);
  } else {
    await db.query('UPDATE cart SET quantity=$1 WHERE id=$2 AND user_id=$3', [q, cart_id, req.session.userId]);
  }
  res.redirect('/cart');
});

app.post('/cart/remove', isAuth, async (req, res) => {
  const { cart_id } = req.body;
  await db.query('DELETE FROM cart WHERE id=$1 AND user_id=$2', [cart_id, req.session.userId]);
  res.redirect('/cart');
});

app.get('/checkout', isAuth, async (req, res) => {
  const user = await db.query('SELECT * FROM users WHERE id=$1', [req.session.userId]);
  const cartRes = await db.query('SELECT c.*, s.title, s.price FROM cart c JOIN services s ON c.service_id=s.id WHERE c.user_id=$1', [req.session.userId]);
  res.render('checkout', { user: user.rows[0], cart: cartRes.rows, message: null });
});

app.post('/checkout', isAuth, async (req, res) => {
  const { payment_method, delivery_address, notes } = req.body;
  const cartRes = await db.query('SELECT c.*, s.price FROM cart c JOIN services s ON c.service_id=s.id WHERE c.user_id=$1', [req.session.userId]);
  if (cartRes.rowCount === 0) return res.redirect('/cart');

  const total = cartRes.rows.reduce((acc, item) => acc + item.price * item.quantity, 0);
  const orderRes = await db.query('INSERT INTO orders(order_number,user_id,total_amount,payment_method,customer_name,customer_email,customer_phone,delivery_address,notes) VALUES($1,$2,$3,$4,$5,$6,$7,$8,$9) RETURNING id', [
    `ORD-${Date.now()}`,
    req.session.userId,
    total,
    payment_method,
    req.session.userName,
    '',
    '',
    delivery_address || '',
    notes || '',
  ]);
  const orderId = orderRes.rows[0].id;

  for (const item of cartRes.rows) {
    await db.query('INSERT INTO order_items(order_id,service_id,product_name,quantity,price,subtotal) VALUES($1,$2,$3,$4,$5,$6)', [
      orderId,
      item.service_id,
      item.title,
      item.quantity,
      item.price,
      item.price * item.quantity,
    ]);
  }
  await db.query('DELETE FROM cart WHERE user_id=$1', [req.session.userId]);
  res.redirect('/');
});

app.get('/admin/login', (req, res) => res.render('admin_login', { message: null }));
app.post('/admin/login', async (req, res) => {
  const { email, password } = req.body;
  const admin = await db.query('SELECT * FROM admin WHERE email=$1', [email]);
  if (admin.rowCount === 0) return res.render('admin_login', { message: 'Invalid credentials' });
  const match = await bcrypt.compare(password, admin.rows[0].password);
  if (!match) return res.render('admin_login', { message: 'Invalid credentials' });
  req.session.adminId = admin.rows[0].id;
  req.session.adminName = admin.rows[0].name;
  res.redirect('/admin/dashboard');
});

app.get('/admin/dashboard', isAdmin, async (req, res) => {
  const counts = {};
  const tables = ['services','users','messages','orders'];
  for(const t of tables){
     const r = await db.query(`SELECT COUNT(*) AS count FROM ${t}`);
     counts[t] = r.rows[0].count;
  }
  res.render('admin_dashboard',{counts,user:req.session.adminName});
});

app.listen(PORT, () => console.log(`Server running on port ${PORT}`));
