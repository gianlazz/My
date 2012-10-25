define(['jquery', 'tylermenezes/serial-bus'], function(jQuery, SerialBus) {
    return new (function()
    {
        var _this = this;

        /**
         * Raw serial port
         * @type {SerialBus.SerialPort}
         */
        this.rawPort = undefined;

        /**
         * The timeout between RFID swipes, in milliseconds
         * @type {Number}
         */
        this.TimeoutPeriod = 4000;

        /**
         * Function to execute when a card is swiped
         * @type {Function(string)}
         */
        this.OnSwipe = undefined;

        /**
         * Prevents double-reads by storing whether the reader is temporarally disabled
         * @type {Boolean}
         */
        var _hasRecievedTag = false;

        Object.defineProperty(this, 'IsAvailable', {
            get: function()
            {
                return (typeof(_this.rawPort) !== 'undefined');
            }
        })

        this._port = undefined;
        Object.defineProperty(this, 'PortNumber', {
            set: function(val)
            {
                _this._port = val;
                _this.constructor();
            },
            get: function()
            {
                return _this._port;
            }
        })

        this.constructor = function()
        {
            if (location.protocol === 'https:') {
                log("Cannot connect to SerialServe over https.");
                return;
            }

            if (typeof(this._port) !== 'undefined') {
                var selectedPort = this._port;
            } else {
                var ports = SerialBus.List;
                var selectedPort = ports[ports.length - 1];
            }
            // Check if the SerialServe server is up, and if so, connect to the RFID reader!
            if (SerialBus.IsRunning && SerialBus.PortAvailable(selectedPort)) { // Check if COM3 is available, since that's where the RFID reader binds...
                log('RFID is available');
                _this.rawPort = new SerialBus.SerialPort(selectedPort);
                _this.rawPort.Enable();
                window.onbeforeunload = function() {
                    _this.rawPort.Disable();
                }
                _this.rawPort.OnDataReceived.register(function(data)
                {
                    if (!_hasRecievedTag) {
                        // Disable the reader
                        _hasRecievedTag = true; // It will take ~300ms to update the hardware, so set this to prevent double-reads
                        _this.rawPort.Disable(); // Disable the hardware

                        // Call the handler
                        if (typeof(_this.OnSwipe) !== 'undefined') {
                            _this.OnSwipe(data);
                        }

                        // Re-enable the port in a few seconds
                        setTimeout(function(){
                            _hasRecievedTag = false;
                            _this.rawPort.Enable();
                        }, _this.TimeoutPeriod);
                    }
                });
                _this.rawPort.Enable();
            } else {
                log('RFID not available');
            }
        }
        this.constructor();
    })();
});
