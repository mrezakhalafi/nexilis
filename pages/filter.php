<div id="searchFilter-a">
    <form id="searchFilterForm-a" action="search-result" method=GET>
                <?php
                $query = "";
                if (isset($_REQUEST['query'])) {
                    $query = $_REQUEST['query'];
                }
                echo '<input id="query" name="query" type="text" class="search-query" onclick="onFocusSearch()" value="' . $query . '"/>';
                echo '<img class="d-none" id="delete-query" src="../assets/img/icons/X-fill-(Black).png">';
                echo '<img id="voice-search" src="../assets/img/icons/Voice-Command-(Black).png">';
                ?>
    </form>
</div>