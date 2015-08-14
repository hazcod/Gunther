<?php
class Accesslog_m extends Core_db
{

   public function getPendingLogs()
   {
        $handle = fopen("/var/log/nginx/webdav.log", "r") or $handle = false;
        if ($handle) {
            while (!feof($handle)) {
                $line = fgets($handle);
                if (trim($line) != ""){
                  //  [20/Jul/2015:10:55:39 +0200] - (94.225.189.110:58918) : /webdav/Films/ (1437382539.241)
                  $parts = explode('-', $line);
                  $date = trim(substr($parts[0], 1, strpos($parts[0], ']') - 1));

                  $user = trim(substr($parts[1],
                                    strpos($parts[1], '(') +1,
                                    strpos($parts[1], ')') - strpos($parts[1], '(') -1
                                    )
                             );

                  $file = trim(substr($parts[1],
                    strrpos($parts[1], ':') +1,
                    strrpos($parts[1], '(') - strrpos($parts[1], ':') -2
                    )
                 );

                  $msec = substr($parts[1], strpos($parts[1], ':'));
                  $msec = substr($msec,
                                 strrpos($msec, '(') +1,
                                 strrpos($msec, ')') - strrpos($msec, '(') -1
                                );

                  $this->addAccessLog($date, $user, $user, $file, $msec);
              }
            }
            ftruncate($handle, 0);
            fclose($handle);
        } else {
            error_log('Accesslog processor could not read /var/log/nginx/webdav.log');
        }
   }

   private function addAccessLog($date, $user, $ip, $file, $msec)
   {
        //req_movies (date DATESTR, file TEXT NOT NULL, user SMALLINT REFERENCES users(id), ip varchar(39));",
        $query = "INSERT INTO req_movies(date, file, user, ip, msec) values (?, ?, ?, ?, ?);";

        $this->db->query($query, array(
                $date,
                $file,
                $user,
                $ip,
                $msec
            ));
   }

}