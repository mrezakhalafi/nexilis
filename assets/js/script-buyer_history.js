let salesHistory = [];
let store_name = "";

function numberWithDots(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function fillHistoryContainer(div) {
    $(div).empty();
    console.log(salesHistory);
    salesHistory.forEach(hist => {
        console.log('start fill');
        let trxItems = '';
        console.log(hist.items);
        hist.items.forEach(its => {
            trxItems += `
            <tr>
                <td class="item-name">${its.item_name}</td>
                <td class="item-qty">${its.item_qty}</td>
                <td class="item-price-total">Rp ${numberWithDots(its.item_price_total)}</td>
            </tr>
            `;
        });
        // console.log(trxItems);
        let trx = `
        <div class="row mb-3">
        <div class="col-sm-10 mx-auto">
          <div class="card">
            <div class="card-body">
              <h6 class="card-title trx-id my-0">${hist.id}</h6>
              <span class="card-subtitle trx-date text-muted">${hist.date}</span>
              <div class="table-responsive mt-3">
                <table class="item-list table table-striped">
                    <thead>
                        <tr>
                            <th class="item-name">Nama Barang</th>
                            <th class="item-qty">Jumlah</th>
                            <th class="item-price-total">Sub-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${trxItems}
                    </tbody>
                </table>
                </div>
            </div>
            <div class="card-footer">
              <table class="trx-total">
                <tbody>
                  <tr>
                    <td class="total-label">
                      <strong>Total</strong>
                    </td>
                    <td class="total-value">
                      <strong>Rp ${numberWithDots(hist.price_total)}</strong>
                    </td>
                  </tr>
                  <tr>
                    <td class="total-label">
                      <strong>Metode Pembayaran</strong>
                    </td>
                    <td class="total-name">
                      <strong>${hist.method}</strong>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
        `;
        $(div).append(trx);
    });

}

function fetchSalesHistory(id) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let data = JSON.parse(xhr.responseText);

            // console.log(data);
            salesHistory = data;
            if (salesHistory.length == 0) {
                console.log('no data');
                $("#no-history").removeClass("d-none");
            } else {
                console.log(data);
                $("#no-history").addClass("d-none");
                // draw on finish
                fillHistoryContainer('#history-container');
            }

        }
    }

    xhr.open('GET', '/nexilis/logics/fetch_shopping_history_buyer?f_pin=' + id);
    xhr.send();
}

$(function () {
    let store_id = new URL(location.href).searchParams.get('f_pin');
    // let store_id = "02b3c7f2db";
    fetchSalesHistory(store_id);
})