## Synopsis

This Woocommerce plugin was designed to update orders using XML files provided by our fulfillment warehouse.

## Process

The fulfillment warehouse uploads XML files with order numbers and tracking numbers to an FTP Server.  This plugin will then check for new files on that server.  If a new file is found, it is downloaded and processed.  Orders are updated to completed and the tracking number is recorded.

This plugin allows you to set FTP credentials, determine how often the FTP server is checked  and saves past order files.

## Notice

This plugin was designed based on the XML file provided by our fulfillment service. To work for your solution, you may need to update the orderupdate_class.php file. Check out the example XML file to see what we were working with.

## License

All code included is Apache License.

Copyright (C) 2013, FullContact and contributors

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
