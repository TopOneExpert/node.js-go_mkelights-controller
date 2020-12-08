package main

import (
	"github.com/Cryptkeeper/go-lightorama/pkg/lor"
	"github.com/tarm/serial"
	"log"
	"math/rand"
	"time"
	"os"
	"strconv"
)

func main() {

    var arg string = "01"

    if len(os.Args) > 1 {
        arg = os.Args[1]
    }

    channel, err := strconv.ParseInt(arg, 10, 8)

    //log.Println(lor.Unit(channel))

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

	//_, _ = cont.On(lor.Channel(channel));

	log.Println("Connected to LOR unit!")

	for range time.Tick(lor.DefaultHeartbeatRate) {
		// Maintain the connection by consistently sending the heartbeat packet
		if _, err := cont.Heartbeat(); err != nil {
			log.Fatal("Lost connection!")
		}

		// Constantly randomize the brightness of channel 1
		//if _, err := cont.SetBrightness(0, rand.Float64()); err != nil {
		if _, err := cont.On(lor.Channel(channel)); err != nil {
			log.Fatal("Failed to set on!")
		}

		//cont.SetEffect(0, );
	}
}
