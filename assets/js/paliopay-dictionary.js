var defaultLang = "id";

var dictionary = {
    notice: {
        title: {
            id: "Peringatan",
            en: "Notice"
        },
        emptyCart: {
            text: {
                id: "Keranjang Kamu kosong!",
                en: "Your cart is empty!"
            }
        },
        successAdd: {
            text: {
                id: "Barang berhasil ditambahkan ke keranjang!",
                en: "Item succesfully added to your cart!"
            }
        },
        successClear: {
            text: {
                id: "Keranjang berhasil dikosongkan!",
                en: "Your cart is now empty!"
            }
        }
    },
    addItem: {
        notice: {
            isBelowOne: {
                id: "Jumlah pesanan minimum adalah satu!",
                en: "The minimum order quantity is one!"
            },
            isDecimal: {
                id: "Jumlah pesanan tidak boleh dalam desimal!",
                en: "Order quantities cannot be in decimals!"
            },
            isEmpty: {
                id: "Jumlah pesanan tidak boleh kosong atau mengandung karakter!",
                en: "Order quantities cannot be blank or contain characters!"
            }
        },
        title: {
            id: "Tambahkan item ini ke keranjang Kamu?",
            en: "Add this item to your cart?"
        },
        buttons: {
            yes: {
                id: "Ya",
                en: "Yes"
            },
            no: {
                id: "Tidak",
                en: "No"
            }
        }
    },
    deleteItem: {
        notice: {
            id: "Hapus barang ini dari keranjang?",
            en: "Delete this item from shopping cart?"
        },
        buttons: {
            yes: {
                id: "Ya",
                en: "Yes"
            },
            no: {
                id: "Tidak",
                en: "No"
            }
        }
    },
    cart: {
        title: {
            id: "Keranjang Kamu",
            en: "Your cart"
        },
        order: {
            id: "Pesanan Kamu",
            en: "Your Order"
        },
        buttons: {
            checkout: {
                id: "Bayar",
                en: "Checkout"
            },
            clear: {
                id: "Hapus",
                en: "Clear"
            }
        },
        notice: {
            minimalOneSelected: {
                id: "Item yang Kamu pilih kurang dari satu.",
                en: "The item you selected is less than one."
            },
            clearCart: {
                id: "Kosongkan keranjang?",
                en: "Empty cart?"
            }
        }
    },
    checkout: {
        title: {
            id: "Pilih metode pembayaran Kamu",
            en: "Choose your payment method"
        },
        buttons: {
            id: "Bayar",
            en: "Pay"
        },
        notice: {
            emptyForm: {
                id: "Formulir tidak boleh kosong!",
                en: "Form can't be empty!"
            },
            pleaseWait: {
                id: "Mohon tunggu...",
                en: "Please wait..."
            },
            success: {
                id: "Transaksi berhasil",
                en: "Transaction success"
            },
            failed: {
                id: "Transaksi gagal",
                en: "Transaction failed"
            },
            error: {
                id: "Terjadi kesalahan",
                en: "Error occured"
            }
        }
    },
    shipping: {
        title: {
            id: "Informasi Pengiriman",
            en: "Shipping Info"
        },
        buttons: {
            ok: {
                id: "OK",
                en: "OK"
            },
            cancel: {
                id: "Batal",
                en: "Cancel"
            }
        }
    }
}

function changeLang(lang){

    defaultLang = lang;

}