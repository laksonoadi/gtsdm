/*
	Pembuat: Chandra Jatnika
	Website: chandrajatnika.com
	Nama   : T10 Tabs
	Tujuan : Membuat Tab Halaman
*/
var T10Tabs = Class.create(
  { 
    // method initialize adalah konstruktor dari class T10Tabs
	initialize: function(tabprefix,contentprefix){ 
		/* tidak melakukan apa-apa, anda bisa menambahkan beberapa 
		   perintah disini apabila ingin mengembangkan */
	},
	addTab: function(tabID,tabContentID){
		this.setNormalStyle(tabID); // Membuat element tabID menjadi style normal (belum diseleksi)
		$(tabContentID).setStyle({
				padding: '4px',
				border: '1px solid #000',
				backgroundColor: '#B4D4F1',
				clear: 'left', // untuk menghilangkan efek 'float' dari style tab
				display: 'none', // secara default tab content tidak ditampilkan terlebih dahulu
				height: '200px'			
			});
		T10Tabs.tab_id.push(tabID); // simpan element tab ke dalam array tab_id
		T10Tabs.tab_content.push(tabContentID); // simpan element content tab ke dalam array tab_content
		
		var index = T10Tabs.tab_id.length - 1; // ambil index terakhir dengan cara = panjang array tab dikurang 1
		Event.observe(tabID,'click', // apabila tab di click maka tampilkan content-nya
				function(){
					(new T10Tabs()).showTab(index);
				}
		 	);
	},
	setNormalStyle: function(tabID){ // ubah menjadi style normal (belum diseleksi)
		$(tabID).setStyle({
				padding: '4px',
				border: '1px solid #000',
				backgroundColor: '#FFF',
				float: 'left', // diberi nilai float agar element merata ke kiri (walaupun element tsb adalah div)
				marginLeft: '1px', // memberi jarak antar tab (anda bisa custom sendiri)
				cursor: 'pointer',
				position: 'relative', // nilai ini harus ada untuk pengaturan tab selected nantinya
				zIndex: 5, // untuk mengedepankan element tab agar bisa 'menindih' content tab pada saat diseleksi
				top: '1px' // akan diubah menjadi 2px apabila tab tsb dipilih (sudah terseleksi)
			});	
	},
	setSelectedStyle: function(tabID){ // ubah menjadi style selected (sudah terseleksi)
		$(tabID).setStyle({
				top: '2px', // dengan sendirinya tab tsb 'menindih' element content sehingga kelihatan menyatu dengan content tsb
				backgroundColor: '#B4D4F1', // background disamakan dengan background content agar kelihatan seperti menyatu
				borderBottom: '0px' // dikosongkan border bottom agar tab dengan content tidak ada garis pemisah dan kelihatan menyatu
			});	
	},
	showTab: function(index){
		if(T10Tabs.tab_id[index] == undefined) return; // jika tab dengan index yg disebut tidak ada maka tidak mengerjakan apa-apa
		T10Tabs.tab_id.each( // semua tab di jadikan style normal terlebih dahulu
				function(element){
					(new T10Tabs).setNormalStyle(element);
				}
			);		
		T10Tabs.tab_content.each( // semua tab content di sembunyikan terlebih dahulu
				function(element){
					$(element).hide();
				}
			);
		this.setSelectedStyle(T10Tabs.tab_id[index]); // jadikan tab dengan index yg dikirimkan dengan style selected tab
		$(T10Tabs.tab_content[index]).show(); // tampilkan content tab
		
		/* simpan tab yang sedang aktif dengan cookie
		   sehingga ketika di refresh dapat kembali ke tab terakhir */
		var currentDate = new Date();
		currentDate.setDate( currentDate.getDate() + 2 ); // set expired date sampai dengan tanggal besok
		document.cookie = this.cookieName+"="+index+"; expires="+currentDate.toGMTString()+"; path=/";
	},
	getLastTab: function(){ // fungsi untuk mendapatkan tab terakhir
	  var results = document.cookie.match ( '(^|;) ?' + this.cookieName + '=([^;]*)(;|$)' );
	  if ( results )
		return ( unescape ( results[2] ) );
	  else
		return 0;		
	},
	cookieName: 'tabCookie' // nama cookie untuk menyimpan tab yang sedang aktif
  }
);
T10Tabs.tab_id = []; // untuk menyimpan data tab secara array numeric
T10Tabs.tab_content = []; // untuk menyimpan data content tab secara array numeric
