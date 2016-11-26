Untuk Pembuatan Template Untuk email harap memperhatikan ketentuan berikut:
1. Baris Pertama setiap template merupakan subject dari email tersebut sedangkan baris2 berikutnya adalah body dari email
2. Jika ada suatu nilai yang ingin diganti secara dinamis maka nilai komponen array 'replace' pada array body yang dijadikan paramater
   pada pemanggilan getBody disesuaikan dengan jenisnya
   misal yang ingin diganti {NAMA_PEGAWAI} dengan variabel nama_pegawai maka isi parameter getBody adalah
   $arrBody[0]['replace']='{NAMA_PEGAWAI}';
   $arrBody[0]['with']=$nama_pegawai;
      

by. Wahyono