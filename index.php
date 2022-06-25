<?php

header('HTTP/1.1 401 Unauthorized');
http_response_code(401);
print(http_response_code());
