<template>
  <div class="chat-panel">
    <div v-if="receiver">
      <h2>{{ receiver.name }}</h2>
    </div>
    <div v-else>
      <h2>Select user</h2>
    </div>
    <div>
      <div
          v-for="(message, index) in messages" :key="index"
          :class="[receiver.id === message.sender_id ? 'receiver' : 'sender']"
      >
        <div class="message-wrapper">
          <p>{{ message.text }}</p>
        </div>
      </div>
      <div>
        <textarea v-model="newMessage" placeholder="send message"></textarea><br>
        <button @click="sendMessage">send</button>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import CryptoJS from 'crypto-js'
import { mapGetters } from 'vuex'

export default {
  name: 'ChatComponent',
  data () {
    return {
      receiver: null,
      sender: null,
      messages: [],
      echo: null,
      newMessage: null
    }
  },

  watch: {
    '$route.params.userId': {
      immediate: true,
      handler(userId) {
        this.findUser(userId)
            .then((receiver) => {
              this.receiver = receiver
              this.sender = this.$store.getters.user.id

              const options = {
                broadcaster: 'pusher',
                key: '8727c0311b3a12a84812',
                cluster: 'eu',
                authEndpoint: 'http://api.local:8000/broadcasting/auth',
                auth: {
                  headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('accessToken'),
                  }
                }
              }

              this.echo = new Echo({
                ...options,
                client: new Pusher(options.key, options)
              })

              this.echo.private(this.getChannel())
                  .listen('.message.sent', (event) => {
                    this.messages.push(event)
                  })
            })
            .then(() => this.loadMessages())
      }
    }
  },
  beforeRouteUpdate () {
    if (this.echo) {
      this.echo.leave(this.getChannel())
    }
  },
  computed: {
    ...mapGetters({
      'sender': 'user'
    })
  },
  methods: {
    getChannel() {
      return 'chat.' + CryptoJS.MD5([this.sender, this.receiver.id].sort().join(''))
    },
    findUser (id) {
      return axios.get('http://api.local:8000/api/user/' + id)
          .then((response) => response.data)
    },
    loadMessages () {
      return axios.get('http://api.local:8000/api/chat/messages/' + this.receiver.id)
          .then((response) => this.messages = response.data)
    },
    sendMessage () {
      const headers = {
        'X-Socket-Id': this.echo.socketId()
      }
      axios
          .post('http://api.local:8000/api/chat/send/' + this.receiver.id,
              { 'message': this.newMessage },
              { headers: headers }
          )
          .then((response) => {
            this.messages.push(response.data)

            this.newMessage = null
          })
          .catch((error) => console.log(error))
    }
  }
}
</script>
<style>
.chat-panel {
  width: 600px;
  margin: 0 auto;
  padding: 0 10px;
  background-color: #fff;
  overflow-y: auto;
}

.receiver {
  text-align: right;
}

.sender {
  text-align: left;
}

.sender > .message-wrapper{
  background: #0f6674;
  color: #fff;
}

.message-wrapper {
  background-color: #f0f0f0;
  padding: 0 10px;
  margin-bottom: 10px;
  border-radius: 20px;
  display: inline-block;
}
</style>