package main

import (
	"github.com/Cryptkeeper/go-lightorama/pkg/lor"
	"github.com/tarm/serial"
	"log"
	"time"
	"os"
	"strconv"
)

func main() {
    var arg_channel string = "01"

    if len(os.Args) > 0 {
        arg_channel = os.Args[1]
        log.Println(arg_channel)
    }

    channel, err := strconv.ParseInt(arg_channel, 10, 8)

	// Open the serial port used for communications with the unit
	port, err := serial.OpenPort(&serial.Config{
		Name: "/dev/ttyUSB0",
		Baud: 19200,
	})

	if err != nil {
		log.Fatal(err)
	}

	var cont = lor.NewController(0x01, port)

	// Write an initial connection heartbeat
    _, _ = cont.Heartbeat()

	log.Println("Connected to LOR unit!")

	for range time.Tick(lor.DefaultHeartbeatRate) {
		// Maintain the connection by consistently sending the heartbeat packet
		if _, err := cont.Heartbeat(); err != nil {
			log.Fatal("Lost connection!")
		}

        if _, err := cont.SetBrightness(lor.Channel(channel-1), 1.0); err != nil {
            log.Fatal("Failed to set off!")
        }
	}
}
