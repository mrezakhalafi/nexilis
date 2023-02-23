import { tab3profile } from '../../language/tab3-profile.js';
import { tab5 } from '../../language/tab5.js';
import { tab5main } from '../../language/tab5-main.js';
import { tab5collectionself } from '../../language/tab5-collection-self.js';
import { tab5recentpurchases } from '../../language/tab5-recent-purchases.js';
import { tab5shopmanager } from '../../language/tab5-shop-manager.js';
import { tab5orders } from '../../language/tab5-orders.js';
import { tab5receipt } from '../../language/tab5-receipt.js';
import { tab5wishlist } from '../../language/tab5-wishlist.js';
import { tab5deliveryaddress } from '../../language/tab5-delivery-address.js';
import { tab5coupons } from '../../language/tab5-coupons.js';
import { tab5store } from '../../language/tab5-store.js';
import { tab5ordersrevenue } from '../../language/tab5-orders-revenue.js';
import { tab5viewers } from '../../language/tab5-viewers.js';
import { tab5followers } from '../../language/tab5-followers.js';
import { tab5createlivestream } from '../../language/tab5-create-live-stream.js';
import { tab5uploadlisting } from '../../language/tab5-upload-listing.js?v=2';
import { tab5yourorders } from '../../language/tab5-your-orders.js';
import { tab5listing } from '../../language/tab5-listing.js';
import { tab5shipping } from '../../language/tab5-shipping.js';
import { tab5shopsettings } from '../../language/tab5-shop-settings.js';
import { tab5appnotification } from '../../language/tab5-app-notification.js';
import { tab5shop } from '../../language/tab5-shop.js';
import { tab5openshop } from '../../language/tab5-open-shop.js';
import { tab5successopenshop } from '../../language/tab5-success-open-shop.js';
import { tab5orderdetails } from '../../language/tab5-order-details.js';
import { tab5newpost } from '../../language/tab5-new-post.js';
import { tab5ads } from '../../language/tab5-ads.js';
import { tab5promotioninsight } from '../../language/tab5-promotion-insight.js';
import { tab5createanad } from '../../language/tab5-create-an-ad.js';
import { tab5adreview } from '../../language/tab5-ad-review.js';
import { tab5finance } from '../../language/tab5-finance.js';
import { tab5changeaddress } from '../../language/tab5-change-address.js';
import { tab5following } from '../../language/tab5-following.js';
import { tab5help } from '../../language/tab5-help.js';
import { tab5security } from '../../language/tab5-security.js';
import { tab5notifications } from '../../language/tab5-notifications.js';
import { tab5settings } from '../../language/tab5-settings.js';
import { tab5inserthighlight } from '../../language/tab5-insert-highlight.js';
import { comment } from '../../language/comment.js';

if(localStorage.lang == null){
    localStorage.lang = 0;
}

var dictionary = Object.assign({}, tab3profile, tab5, tab5main, tab5collectionself, tab5recentpurchases, tab5shopmanager,
                tab5orders, tab5receipt, tab5wishlist, tab5deliveryaddress, tab5coupons,
                tab5store, tab5ordersrevenue, tab5viewers, tab5followers, tab5createlivestream,
                tab5uploadlisting, tab5yourorders, tab5listing, tab5shipping, tab5shopsettings,
                tab5appnotification, tab5shop, tab5openshop, tab5successopenshop, tab5orderdetails,
                tab5newpost, tab5ads, tab5promotioninsight, tab5createanad, tab5adreview, tab5finance,
                tab5changeaddress, tab5following, tab5help, tab5security, tab5notifications, tab5settings,
                tab5inserthighlight, comment);

var langs = ['en', 'id'];
var current_lang_index = localStorage.lang;
var current_lang = langs[current_lang_index];

window.change_lang = function (lang) {
    current_lang = langs[lang];

    translate();
}

function translate() {
    $("[data-translate]").each(function () {
        var key = $(this).data('translate');
        $(this).html(dictionary[key][current_lang] || "N/A");
    });
}

change_lang(localStorage.lang);