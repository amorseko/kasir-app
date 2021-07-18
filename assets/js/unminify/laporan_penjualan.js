let laporan_penjualan = $("#laporan_penjualan").DataTable( {
    responsive:true,
    scrollX:true,
    ajax:readUrl,
    columnDefs:[{
        searcable: false,
        orderable: false,
        targets: 0
    }],
    order:[
        [1, "asc"]],
        columns:[ {
            data: null
        }
        , {
            data: "tanggal"
        }
        , {
            data: "nama_produk"
        }
        , {
            data: "total_bayar"
        }
        , {
            data: "jumlah_uang"
        }
        , {
            data: "diskon"
        }
        , {
            data: "pelanggan"
        }
        , {
            data: "action"
        }
        ]
}

);

function cari()
{
	laporan_penjualan.destroy
	let tgl_from = $("#tgl_from").val(),
		tgl_to = $("#tgl_to").val();

	if(tgl_from == "")
	{
		Swal.fire("Peringatan", "Mohon isi tanggal terlebih dahulu", "warning");
		return;
	}

	if(tgl_to == "")
	{
		Swal.fire("Peringatan", "Mohon isi tanggal terlebih dahulu.", "warning");
		return;
	}



	var tgl_from_fix = tgl_from.replace(/\//g, "-");
	var tgl_to_fix = tgl_to.replace(/\//g, "-");
	console.log(tgl_to_fix);
	laporan_penjualan.ajax.url(readUrl + "/" + tgl_from_fix + "/" + tgl_to_fix + "/").load();
	// $("#laporan_penjualan").DataTable( {
	// 	responsive:true,
	// 	scrollX:true,
	// 	ajax:readUrl,
	// 	columnDefs:[{
	// 		searcable: false,
	// 		orderable: false,
	// 		targets: 0
	// 	}],
	// 	order:[
	// 		[1, "asc"]],
	// 		columns:[ {
	// 			data: null
	// 		}
	// 		, {
	// 			data: "tanggal"
	// 		}
	// 		, {
	// 			data: "nama_produk"
	// 		}
	// 		, {
	// 			data: "total_bayar"
	// 		}
	// 		, {
	// 			data: "jumlah_uang"
	// 		}
	// 		, {
	// 			data: "diskon"
	// 		}
	// 		, {
	// 			data: "pelanggan"
	// 		}
	// 		, {
	// 			data: "action"
	// 		}
	// 		]
	// }
	
	// );
}

$(function () {
    //Date picker
    $('#tgl_from').datepicker({
	  autoclose: true,
	  format: 'mm/dd/yyyy',
    })
})

$(function () {
	//Date picker
	$('#tgl_to').datepicker({
		autoclose: true,
		format: 'mm/dd/yyyy',
	})
})
function reloadTable() {
    laporan_penjualan.ajax.reload()
}

function remove(id) {
    Swal.fire( {
        title: "Hapus",
        text: "Hapus data ini?",
        type: "warning",
        showCancelButton: true
    }).then((result)=> {
		if(result.dismiss != 'cancel'){
			$.ajax( {
				url:deleteUrl,
				type:"post",
				dataType:"json",
				data: {
					id: id
				},
				success:()=> {
					Swal.fire("Sukses", "Sukses Menghapus Data", "success");
					reloadTable()
				},
				error:err=> {
					console.log(err)
				}
			})
		}
    })
}

laporan_penjualan.on("order.dt search.dt", ()=> {
    laporan_penjualan.column(0, {
        search: "applied", order: "applied"
    }).nodes().each((el, err)=> {
        el.innerHTML=err+1
    })
});
$(".modal").on("hidden.bs.modal", ()=> {
    $("#form")[0].reset();
    $("#form").validate().resetForm()
});
