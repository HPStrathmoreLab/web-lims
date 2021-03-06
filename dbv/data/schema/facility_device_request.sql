CREATE TABLE `facility_device_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facility_id` varchar(50) NOT NULL,
  `requested_by` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `request_status` int(11) DEFAULT NULL,
  `date_requested` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `serial_number` varchar(20) NOT NULL,
  `ctc_id_no` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1