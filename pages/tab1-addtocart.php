

        <!-- <div class="row"> -->
            <div class="col-12 px-0">
                <div class="addcart-img-container text-center">
                    <hr id="drag-this">
                    <img id="btn-wishlist" class="addcart-wishlist" src="../assets/img/icons/Wishlist-(White).png">
                </div>
            </div>
        <!-- </div> -->

        <div class="prod-name-price">
            <div class="col-12">
                <h5 class="product-name"></h5>
                <h5 class="product-price"></h5>
            </div>
        </div>

        <div class="m-3 prod-details">
            <div class="col-11">
            </div>
        </div>

        <div class="row prod-addtocart">
            <div class="col-4 px-2">
                <div class="input-group counter">
                    <button class="btn btn-outline-secondary btn-decrease" type="button" onclick="changeItemQuantity('modal-item-qty','sub')">-</button>
                    <input id="modal-item-qty" type="text" pattern="\d*" maxlength="3" class="form-control text-center" placeholder="" value="1" min="1">
                    <button class="btn btn-outline-secondary btn-increase" type="button" onclick="changeItemQuantity('modal-item-qty','add')">+</button>
                </div>
            </div>
            <div class="col-8">
                <button id="add-to-cart" class="btn btn-addcart w-100">Add to Cart</button>
            </div>
        </div>
    <!-- </div> -->

        <script>
            $('#btn-wishlist').click(function() {
                if ($(this).attr('src') == '../assets/img/icons/Wishlist-White-fill.png') {
                    $(this).attr('src', '../assets/img/icons/Wishlist-(White).png');
                } else {
                    $(this).attr('src', '../assets/img/icons/Wishlist-White-fill.png');
                }
            })
        </script>