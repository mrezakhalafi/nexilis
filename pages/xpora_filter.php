<div id="searchFilter" class="d-none">
    <form id="searchFilterForm">
        <div id="searchbar">
            <div class="input-group">
                <?php
                $query = "";
                if (isset($_REQUEST['query'])) {
                    $query = $_REQUEST['query'];
                }
                echo '<input id="query" type="text" class="search-query form-control" placeholder="Cari Toko" onclick="onFocusSearch()" value="' . $query . '"/>';
                ?>
                <img id="mic" src="../assets/img/action_mic.png" onclick="voiceSearch()">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </div>
        </div>
        <div>
            <label class="form-check-label cl-switch" for="switchAll">
                <span class="label">Semua</span>
                <input class="checkbox-filter form-check-input" type="checkbox" id="switchAll" <?php echo (isset($_REQUEST["filter"]) && $_REQUEST["filter"] != "1-2-3" ? '' : 'checked'); ?>>
                <span class="switcher"></span>
            </label>
        </div>
        <div>
            <?php
            $filters = include_once $_SERVER['DOCUMENT_ROOT'] . '/nexilis/logics/fetch_stores_category.php';
            for ($i = 0; $i < count($filters); $i++) {
                $idFilter = $filters[$i]["ID"];
                if($idFilter == "4") continue;
                $nameFilter = $filters[$i]["NAME"];
                echo '<div class="form-check-inline">';
                echo '<input class="checkbox-filter checkbox-filter-cat form-check-input" name="category[]" type="checkbox" id="checkboxFilter-' . $idFilter . '" data-value=' . $idFilter . ' ' . (isset($_REQUEST["filter"]) && strpos($_REQUEST["filter"], $idFilter) === false ? '' : 'checked') . '>';
                echo '<label class="form-check-label" for="checkboxFilter-' . $idFilter . '">' . $nameFilter . '</label>';
                echo '</div>';
            }
            ?>
        </div>
        <div id="checkboxGroup"></div>
    </form>
</div>