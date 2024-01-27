require('dotenv').config()
const Redis = require("ioredis")

const io = require('socket.io')(process.env.SOLI_SOCKETIO_SOCKET_PORT, {
    cors: {
        origin: "*",
    }
})

const redis = new Redis(process.env.SOLI_SOCKETIO_REDIS_PORT)

io.on("error", (socket) => {
  // console.log("Socket io error")
})

io.on("connection", (socket) => {
  console.log("Co nguoi ket noi " + socket.id)

  socket.on('disconnect', () => {
    console.log(socket.id + ' ngat ket noi')
  })

  socket.on('rooms:join', (data) => {
    data.room_ids.forEach(roomId => {
      socket.join(roomId)
    })
  })

})

redis.psubscribe("*", (err, count) => {
  if (err) {
    console.error("Failed to subscribe: %s", err.message)
  } else {
    console.log(
      `Subscribed successfully! This client is currently subscribed ${count} channels.`
    )
  }
})

redis.on("pmessage", (partner, channel, message) => {
  try {
    if (channel === "emit-data-to-socket-io") {
      const {data} = JSON.parse(message)
      if (data.roomId) {
        io.sockets.in(data.roomId).emit(data.event, data.data)
      } else {
        io.emit(data.event, data.data)
      }
    }
  } catch (error) {
    console.log(message)
  }
})
