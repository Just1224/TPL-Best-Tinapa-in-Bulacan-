const express = require('express');
const path = require('path');
const app = express();

app.use(express.static('../frontend'));
app.use(express.json());

app.get('/api/products', (req, res) => {
  res.json([{id:1, name:'Tinapa'}]);
});

app.listen(3000, () => console.log('Server on 3000'));

