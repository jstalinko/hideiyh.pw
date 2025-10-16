<?php
namespace App\Helpers;


Class Helper{

   public static function generateApikey(string $string): string
    {
        // 1. Lakukan hashing berlapis sesuai permintaan untuk mendapatkan sumber acak
        $s1 = sha1($string . time() . rand(10, 100));
        $s2 = bcrypt($s1); // bcrypt menghasilkan string >60 karakter
        $md5Hash = strtoupper(md5($s2)); // md5 menghasilkan 32 karakter

        // 2. Format 32 karakter MD5 menjadi 5 segmen @ 5 karakter
        //    Fungsi str_split akan memecah string menjadi array: ['ABCDE', 'FGHIJ', ...]
        $chunks = str_split($md5Hash, 5);

        // 3. Ambil hanya 5 segmen pertama untuk mendapatkan total 25 karakter
        //    dan gabungkan dengan tanda hubung "-"
        $formattedKey = implode('-', array_slice($chunks, 0, 5));

        // 4. Tambahkan awalan "XHID-" dan kembalikan hasilnya
        return 'XHID-' . $formattedKey;
    }
}