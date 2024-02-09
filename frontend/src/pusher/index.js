import Pusher from 'pusher-js';

// app_id = "1750400"
// key = "8727c0311b3a12a84812"
// secret = "e35ea26321fdaab5cc60"
// cluster = "eu"

export default new Pusher('8727c0311b3a12a84812', {
  cluster: 'eu',
  encrypted: true
});