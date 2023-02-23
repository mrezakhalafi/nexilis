<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (isset($_REQUEST['store_id'])) {
    $store_id = $_REQUEST['store_id'];
}

if (isset($_REQUEST['f_pin'])) {
    $f_pin = $_REQUEST['f_pin'];
}

// $be_id = $_GET['p'];

$products_raw = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_news_raw.php');


// print_r($products_final);
$products_final = array();
if (isset($store_id)) {
    if ($store_id == "store-nexilis") { // links only
        $products_final = array_filter($products_raw, function($key){
            return $key["IS_POST"] == 0;
        });
    } else if ($store_id != "" && $store_id != "store-nexilis"){ // posts only
        $products_final = array_filter($products_raw, function($key){
            return $key["IS_POST"] == 1;
        });
    } 
} else { // everything
    $products_final = $products_raw;
}

// echo "<pre>";
// print_r($products_final);
// echo "</pre>";


// shuffle the timeline
// shuffle($products_final);

function richText($str) {

    $newStr = preg_replace('/\*([^\*]+)\*/', '<strong>$1</strong>', $str);
    $newStr = preg_replace('/\^([^\^]+)\^/', '<u>$1</u>', $newStr);
    $newStr = preg_replace('/\_([^\_]+)\_/', '<i>$1</i>', $newStr);
    $newStr = preg_replace('/\~([^\~]+)\~/', '<del>$1</del>', $newStr);
    $newStr = preg_replace('/[\n\r]+/', '<br>', $newStr);

    return $newStr;
}



if (empty($products_final) && $_GET['offset'] < 10) {
    echo '<div class="my-2" id="product-null">';
    echo '<div class="col-sm mt-2">';
    echo '<h5 class="prod-name" style="text-align:center; margin-top:165px; font-size: 1.25rem; font-weight: normal" id="no-content-available"></h5>';
    echo '<script>';
    echo 'if (localStorage.lang == 0) {document.getElementById("no-content-available").innerHTML = "Nothing matches your criteria"; $("#story-all-posts").text("All Posts");} else {document.getElementById("no-content-available").innerHTML = "Tidak ada konten yang sesuai dengan kriteria"; $("#story-all-posts").text("Semua Post");}</script>';
    echo '</div>';
    echo '</div>';
} else {

    // check wishlist
    if ($query = $dbconn->prepare("SELECT PRODUCT_CODE FROM WISHLIST_PRODUCT WHERE FPIN = ?")) {
        $query->bind_param('s', $f_pin);
        $query->execute();
        $wishlist = $query->get_result();
        $query->close();
    } else {
        //error !! don't go further
        var_dump($dbconn->error);
    }

    // $wishlists = array();
    // while ($wish = $wishlist->fetch_assoc()) {
    //     $wishlists[] = $wish['PRODUCT_CODE'];
    // };
    // end wishlist

    $image_type_arr = array("jpg", "jpeg", "png", "webp");
    $video_type_arr = array("mp4", "mov", "wmv", 'flv', 'webm', 'mkv', 'gif', 'm4v', 'avi', 'mpg');
    $shop_blacklist = array("17b0ae770cd", "239"); //isi manual 

    $news_fpin = array("02f041b31a", "02d7c16d7a");

    // $is_user_admin = include_once($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_user_type.php');

    for ($i = 0; $i < count($products_final); $i++) {

        $code = $products_final[$i]["CODE"];
        $be_userlist = $products_final[$i]["ENABLE_NEWS"];

        // if (in_array($post_f_pin, $news_fpin)) {
        //     continue;
        // }
        
        $post_f_pin = $products_final[$i]["F_PIN"];  

        // $is_removed = $products_final[$i]["IS_REMOVED"];

        // if ($is_removed == 1) {
        //     continue;
        // }

        $is_block_post = $products_final[$i]["IS_BLOCK_POST"];
        if ($is_block_post > 0) {
            continue;
        }

        $post_score = $products_final[$i]["SCORE"];

        // $name = $products_final[$i]["NAME"];
        $created_date = $products_final[$i]["CREATED_DATE"];
        // $category = $products_final[$i]["CATEGORY"];
        // $classification = $products_final[$i]["CLASSIFICATION"];
        $seconds = intval(intval($created_date) / 1000);
        // // $printed_date = date("H:i", $seconds);

        $lazy = ' loading=lazy';

        // print date
        $date_diff = round((time() - $seconds) / (60 * 60 * 24));
        if ($date_diff == 0) {
            $printed_date = "Today";
        } else if ($date_diff == 1) {
            $printed_date = "Yesterday";
        } else if ($date_diff == 2) {
            $printed_date = "2 days ago";
        } else if ($date_diff == 3) {
            $printed_date = "3 days ago";
        } else if ($date_diff == 4) {
            $printed_date = "4 days ago";
        } else if ($date_diff == 5) {
            $printed_date = "5 days ago";
        } else if ($date_diff == 6) {
            $printed_date = "6 days ago";
        } else if ($date_diff == 7) {
            $printed_date = "7 days ago";
        } else if ($date_diff > 7 && $date_diff < 365) {
            $printed_date = date("j M Y", $seconds);
        } else if ($date_diff >= 365) {
            $printed_date = date("j M Y", $seconds);
        }

        // $store_id = $products_final[$i]["SHOP_CODE"];
        $title = $products_final[$i]["TITLE"];
        $desc = nl2br($products_final[$i]["DESCRIPTION"]);
        $thumb_id = $products_final[$i]["THUMB_ID"];
        $thumbnail = $products_final[$i]["THUMBNAIL"];
        $thumb_ids = array_filter(explode("|", $thumb_id));
        $thumbnail_arr = array_filter(explode("|", $thumbnail));
        // print_r($thumbnail_arr);
        // $store_thumb_id = $products_final[$i]["STORE_THUMB_ID"];
        $store_name = $products_final[$i]["STORE_NAME"];
        // $store_name = "nxBrowser";
        $store_link = $products_final[$i]["STORE_LINK"];
        $total_likes = $products_final[$i]["TOTAL_LIKES"];
        // $total_follower = $products_final[$i]["TOTAL_FOLLOWER"];
        // $total_comment = count(include($_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_products_comments.php'));
        // $use_adblock = $products_final[$i]["USE_ADBLOCK"];
        $is_verified = $products_final[$i]["OFFICIAL_ACCOUNT"];
        // $is_verified = 0;
        $is_product = 0;
        $tagged_product = null;
        $is_paid = 0;
        $is_post = $products_final[$i]["IS_POST"];
        $profpic = $products_final[$i]["PROFPIC"];

        $report = $products_final[$i]["REPORT"];

        $user_has_shop = $products_final[$i]["HAS_SHOP"];

        // if (in_array($store_id, $shop_blacklist)) {
        //     continue;
        // }

        // $imgs = explode('|', $store_thumb_id);
        // if (substr($imgs[0], 0, 4) !== "http") {
        //     $thumb = "/nexilis/images/" . $imgs[0];
        // } else {
        if ($profpic != null && $profpic != '') {
            $thumb = "/filepalio/image/" . $profpic; 
        } else {
            $thumb = "/nexilis/assets/img/ic_person_boy.png";
        }
        // }

        $domain = '';
        // $domain = 'https://newuniverse.io';

        // if ($i > 0) {
        echo '<hr class="my-0">';
        // }

        if ($is_post == 1) {
            echo '<div class="product-row my-2 is-post" id="product-' . $code . '">';
            echo '<div id="be_user-list">' . $be_userlist . '</div>';
        } else {
            echo '<div class="product-row my-2" id="product-' . $code . '">';
            echo '<div id="be_user-list">' . $be_userlist . '</div>';
        }
        echo '<div class="col-sm">';
        echo '<div class="timeline-post-header media">';
        // echo '<a class="d-flex pe-2">';
        // if ($user_has_shop >= 1) {
            echo '<a class="d-flex pe-2" onclick="openProfile(\'tab3-profile.php?f_pin='.$f_pin.'&store_id='.$post_f_pin.'\')">';
        // } else {
        //     echo '<a class="d-flex pe-2" href="tab3-profile-user.php?f_pin='.$f_pin.'&store_id='.$post_f_pin.'">';
        // }
        echo '<img src="' . $thumb . '" class="align-self-start rounded-circle mr-2">';
        echo '</a>';
        echo '<div class="media-body">';
        if ($is_verified == 2) {
            echo '<h5 class="store-name"><img src="/nexilis/assets/img/ic_verified_flag.png"/>' . $store_name . '</h5>';
        } else {
            echo '<h5 class="store-name">' . $store_name . '</h5>';
        }
        echo '<p class="prod-timestamp">' . $printed_date . '</p>';
        echo '</div>';
        echo '<div class="post-status d-none">';
        echo '<img src="../assets/img/ic_public.png" height="20" width="20"/>';
        echo '</div>';
        echo '<div class="post-status d-none">';
        echo '<img src="../assets/img/ic_user.png" height="20" width="20"/>';
        echo '</div>';
        // echo '<div class="post-status" onclick="openProductMenu(\'' . $code . '\')">';
        // if ($post_f_pin == $f_pin) {
            echo '<div class="dropdown dropdown-edit d-none edit-menu-'.$post_f_pin.'" data-isadmin="'.$is_user_admin.'">';
            echo '<a class="post-status dropdown-toggle" data-bs-toggle="dropdown" id="edt-del-'.$code.'"><img src="../assets/img/icons/More.png" height="25" width="25" style="background-color:unset;"/></a>';
            echo '<ul class="dropdown-menu" aria-labelledby="edt-del-'.$code.'">';
            echo '<li><a class="dropdown-item button_edit" onclick="editPost(\''.$code.'\')">Edit</a></li>';
            echo '<li><a class="dropdown-item button_delete" onclick="deletePost(\''.$code.'\')">Delete</a></li>';
            echo '<li><a class="dropdown-item button_adminremove d-none" onclick="confirmRemovePost(\''.$code.'\')">Remove</a></li>';
            echo '</ul>';
        // echo '<img src="../assets/img/icons/More.png" height="25" width="25"/>';
            echo '</div>';
        // }
        echo '</div>';
        echo '</div>';
        echo '<div class="col-sm mt-2 timeline-image">';
        // echo '<a class="timeline-main" onclick="openStore(\'' . $store_id . '\',\'' . $store_link . '\');">';
        // if ($is_paid != 0) {
            echo '<a class="timeline-main" id="detail-product-' . $code . '" onclick="showAddModalPost(\'' . $code . '\');">';
        // } else {
        //     echo '<a class="timeline-main" id="detail-product-' . $code . '">';
        // }

        if (count($thumb_ids) == 1) {
            
            // echo '<img class="single-image img-fluid rounded" src="' . $thumb_id . '">';
            $thumb_ext = pathinfo(parse_url($thumb_ids[0],PHP_URL_PATH), PATHINFO_EXTENSION);
            $image_name = str_replace($thumb_ext, "", $thumb_ids[0]);
            // echo $thumb_ids[0];
            if (in_array($thumb_ext, $image_type_arr)) {
                if (substr( $thumb_ids[0], 0, 4 ) === "http") {
                    echo '<img src="' . $thumb_ids[0] . '" class="img-fluid rounded"' . $lazy . ' onload="pinchZoom(this);">';
                } else {
                    echo '<img src="'.$domain.'/nexilis/images/' . $thumb_ids[0] . '" class="img-fluid rounded"' . $lazy . ' onload="pinchZoom(this);">';
                }
                if ($tagged_product != null) {
                    echo '<div class="timeline-product-tag">';
                    echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                    echo '</div>';
                }
            } else if (in_array($thumb_ext, $video_type_arr)) {
                $check_ext = pathinfo(parse_url($thumbnail_arr[0],PHP_URL_PATH), PATHINFO_EXTENSION);
                if ($thumbnail_arr[0] == "" || $thumbnail_arr[0] == null || !file_exists($_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/' . $thumbnail_arr[0]) || in_array($check_ext, $video_type_arr)) {
                    $vid_placeholder = '/nexilis/assets/img/video_placeholder.jpg'; 
                } else {                   
                    $vid_placeholder = $domain . '/nexilis/images/' . $thumbnail_arr[0];
                }
                echo '<div class="video-wrap" id="videowrap-'.$code.'-0">';
                echo '<video loop autoplay playsinline muted id="video-'.$code.'-0" class="myvid rounded" src="'.$domain.'/nexilis/images/' . $thumb_ids[0] . '#t=1" preload="none" poster="'.$vid_placeholder.'" onloadeddata="pinchZoom(this);"></video>';
                if ($tagged_product != null) {
                    echo '<div class="timeline-product-tag-video">';
                    echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                    echo '</div>';
                }
                echo '<div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute(\'videowrap-'.$code.'-0\');">';
                echo '<img src="../assets/img/video_mute.png" />';
                echo '</div>';
                echo '<div class="video-play d-none">';
                echo '<img src="../assets/img/video_play.png" />';
                echo '</div></div>';
            }
        } else {
            $count_thumb_id = count($thumb_ids);
            echo '<div id="carousel-' . $code . '" class="carousel slide pointer-event" data-bs-touch="true">';
            echo '<ol id="ci-' . $code . '" class=' . '"carousel-indicators">';
            for ($j = 0; $j < $count_thumb_id; $j++) {
                if ($j == 0) {
                    echo '<li data-bs-target="#carousel-' . $code . '" data-bs-slide-to="' . $j . '" class="active"></li>';
                } else {
                    echo '<li data-bs-target="#carousel-' . $code . '" data-bs-slide-to="' . $j . '"></li>';
                }
            }
            echo '</ol>';
            echo '<div class="carousel-inner">';
            for ($j = 0; $j < count($thumb_ids); $j++) {
                if ($j == 0) {
                    echo '<div class="carousel-item active">';
                } else {
                    echo '<div class="carousel-item">';
                }
                echo '<div class="carousel-item-wrap">';
                $thumb_ext = pathinfo(parse_url($thumb_ids[$j],PHP_URL_PATH), PATHINFO_EXTENSION);
                $image_name = str_replace($thumb_ext, "", $thumb_ids[$j]);
                if (in_array($thumb_ext, $image_type_arr)) {
                    // echo '<img src="' . $thumb_ids[$j] . '" class="img-fluid rounded"' . $lazy . '>';
                    if (substr( $thumb_ids[$j], 0, 4 ) === "http") {
                        echo '<img src="' . $thumb_ids[$j] . '" class="img-fluid rounded"' . $lazy . ' onload="pinchZoom(this);">';
                    } else {
                        echo '<img src="'.$domain.'/nexilis/images/' . $thumb_ids[$j] . '" class="img-fluid rounded"' . $lazy . ' onload="pinchZoom(this);">';
                    }
                    // if ($tagged_product != null) {
                    //     echo '<div class="timeline-product-tag">';
                    //     echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                    //     echo '</div>';
                    // }
                } else if (in_array($thumb_ext, $video_type_arr)) {
                    $check_ext = pathinfo(parse_url($thumbnail_arr[$j],PHP_URL_PATH), PATHINFO_EXTENSION);
                    // echo $thumbnail_arr[$j];
                    if ($thumbnail_arr[$j] == '' || $thumbnail_arr[$j] == null || !file_exists($_SERVER['DOCUMENT_ROOT'] . '/nexilis/images/' . $thumbnail_arr[$j]) || in_array($check_ext, $video_type_arr)) {
                        $vid_placeholder = '/nexilis/assets/img/video_placeholder.jpg';
                    } else {
                        $vid_placeholder = $domain . '/nexilis/images/' . $thumbnail_arr[$j];
                    }
                    echo '<div class="video-wrap" id="videowrap-'.$code.'-'.$j.'">';
                    echo '<video loop autoplay playsinline muted id="video-'.$code.'-'.$j.'" class="myvid rounded" src="'.$domain.'/nexilis/images/' . $thumb_ids[$j] . '#t=1" preload="none" poster="'.$vid_placeholder.'" onloadeddata="pinchZoom(this);"></video>';
                    echo '</video>';
                    if ($tagged_product != null) {
                        echo '<div class="timeline-product-tag-video">';
                        echo '<img src="../assets/img/icons/Tagged-Product.png" />';
                        echo '</div>';
                    }
                    echo '<div class="video-sound" onclick="event.stopPropagation(); toggleVideoMute(\'videowrap-'.$code.'-'.$j.'\');">';
                    echo '<img src="../assets/img/video_mute.png" />';
                    echo '</div>';
                    echo '<div class="video-play d-none">';
                    echo '<img src="../assets/img/video_play.png" />';
                    echo '</div></div>';
                }

                echo '</div></div>';
            }
            echo '</div>';
            echo '<a class="carousel-control-prev" data-bs-target="#carousel-' . $code . '" data-bs-slide="prev" onclick="event.stopPropagation();">';
            echo '<span class="carousel-control-prev-icon"></span>';
            echo '</a>';
            echo '<a class="carousel-control-next" data-bs-target="#carousel-' . $code . '" data-bs-slide="next" onclick="event.stopPropagation();">';
            echo '<span class="carousel-control-next-icon"></span>';
            echo '</a>';
            echo '</div>';
        }
        echo '</a>';
        echo '</div>';
        echo '<div class="col-sm mt-2 d-flex align-items-center" class="like-comment-container">';
        echo '<div class="like-button" onClick="likeProduct(\'' . $code . '\', \''. $is_post .'\')">';
        echo '</div>';
        echo '<div class="comment-button">';
        // echo '<a href="comment?product_code=' . $code . '&is_post='. $is_post .'">';
        echo '<a onclick="openComment(\''.$code.'\','.$is_post.')">';
        
        echo '</a>';
        echo '</div>';
        
        echo '</ul>';
        echo '<div class="follower-button" onClick="addWishlist(\'' . $code . '\',this)">';
        if ($is_paid == 1) {
            if (in_array($code, $wishlists)) {
                echo '<img class="follow-icon-' . $store_id . '" src="../assets/img/icons/Wishlist-fill.png" height="25" width="25"/>';
            } else {
                echo '<img class="follow-icon-' . $store_id . '" src="../assets/img/icons/Wishlist.png" height="25" width="25"/>';
            }
        }
        // echo '<div id=follow-counter-post-' . $code . ' class="d-none like-comment-counter follow-counter-store-' . $store_id . '">';
        // echo $total_follower . ' pengikut';
        // echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="col-sm mt-2">';
        echo '<span class="prod-name"><img class="verified-icon-prod d-none" src="../assets/img/icons/Verified-(Black).png">' . richText(urldecode(strip_tags($title))) . '</span>&emsp;';
        // if ($is_paid == 0) {
            echo '<span class="prod-desc">' . richText(urldecode(strip_tags($desc))) . '</span>';
        // } else {
        //     if (in_array($code, $purchases)) {
        //         echo '<span class="prod-desc">' . strip_tags($desc) . '</span>';
        //     } else {
        //         echo '<div class="row my-3">';
        //         echo '<div class="col-sm-12">';
        //         echo '<h5 class="text-center">Purchase to see content</h5>';
        //         echo '</div>';
        //         echo '</div>';
        //     }
        // }
        echo '</div>';
        echo '</div>';
        echo '<script>console.log("'.$code.'","'.$post_score.'");</script>';
    }
    
}

?>

<script>
    if (localStorage.lang == 0) {
        $('.button_edit').text('Edit');
        $('.button_delete').text('Delete');
        $('#story-all-posts').text("All Posts");
        $(".moderation-item-rc").text("Report/flag Content")
        $(".moderation-item-ru").text("Report/flag User")
        $(".moderation-item-bu").text("Remove/Block User")
        $(".moderation-item-bp").text("Remove/Block Content")
    } else {
        $('.button_edit').text('Ubah');
        $('.button_delete').text('Hapus');
        $('#story-all-posts').text("Semua Post");
        $(".moderation-item-rc").text("Laporkan/flag Konten")
        $(".moderation-item-ru").text("Laporkan/flag Pengguna")
        $(".moderation-item-bu").text("Hapus/Blokir Pengguna")
        $(".moderation-item-bp").text("Hapus/Blokir Konten")
    }
</script>