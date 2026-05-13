<?php
try {
    $p = new PDO("pgsql:host=db.ubzqxbnlzqglwwmyjpvc.supabase.co;port=6543;dbname=postgres;sslmode=require", "postgres", "#0987654123Feby", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Success 6543 old pooler!\n";
} catch (Exception $e) {
    echo "Error 6543 old pooler: " . $e->getMessage() . "\n";
}
