## Synopsis

This plugin was designed to update orders using XML files provided by our fulfillment warehouse.

## Process

The fulfillment warehouse uploads XML files with order numbers and tracking numbers to an FTP Server.  This plugin will then check for new files on that server.  If a new file is found, it is downloaded and processed.  Orders are updated to completed and the tracking number is recorded.

This plugin allows you to set FTP credentials, determine how often the FTP server is checked  and saves past order files.


## License

A short snippet describing the license (MIT, Apache, etc.)
