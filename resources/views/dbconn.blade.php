<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>laravel and mysql db connection</title>
</head>
<body>
    <div>

    <?php 
          if(DB::connection()->getpdo()){
            echo "successfully connected to the database: " . DB::connection()->getDatabaseName();
          }

       ?>

    </div>
    
</body>
</html>