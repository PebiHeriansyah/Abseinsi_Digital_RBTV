<?php
try {
    $p = new PDO("pgsql:host=aws-0-ap-south-1.pooler.supabase.com;port=6543;dbname=postgres;sslmode=require", "postgres.ubzqxbnlzqglwwmyjpvc", "#0987654123Feby", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Success 6543!\n";
} catch (Exception $e) {
    echo "Error 6543: " . $e->getMessage() . "\n";
}

try {
    $p = new PDO("pgsql:host=aws-0-ap-south-1.pooler.supabase.com;port=5432;dbname=postgres;sslmode=require", "postgres.ubzqxbnlzqglwwmyjpvc", "#0987654123Feby", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Success 5432 pooler!\n";
} catch (Exception $e) {
    echo "Error 5432 pooler: " . $e->getMessage() . "\n";
}

try {
    $p = new PDO("pgsql:host=db.ubzqxbnlzqglwwmyjpvc.supabase.co;port=5432;dbname=postgres;sslmode=require", "postgres", "#0987654123Feby", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Success 5432 direct!\n";
} catch (Exception $e) {
    echo "Error 5432 direct: " . $e->getMessage() . "\n";
}
