<?php

define("DS", "/");
define("SITE_DIR", "ashish_rest");
define("SITE_URL", "http://" . $_SERVER["SERVER_NAME"] . DS . SITE_DIR);
define("SITE_PATH", $_SERVER["DOCUMENT_ROOT"] . DS . SITE_DIR);
define("CLASS_PATH", SITE_PATH . "/classes");
define("LIB_PATH", SITE_PATH . "/lib");
define("ROWS_PER_PAGE", 15);

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "rest");

define("TBL_USER", "users");
define("TBL_WORK", "works");
