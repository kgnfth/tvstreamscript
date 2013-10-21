<?php 
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}
?>
            <!-- sidebar -->
            <a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">Sidebar switch</a>
            <div class="sidebar">
                
                <div class="antiScroll">
                    <div class="antiscroll-inner">
                        <div class="antiscroll-content">
                    
                            <div class="sidebar_inner">
                                <form action="index.php" class="input-append" method="post" >
                                    <input type="hidden" name="menu" value="search" />
                                    <input autocomplete="off" name="query" class="input-medium" size="16" type="text" placeholder="Search..." /><button type="submit" class="btn"><i class="icon-search"></i></button>
                                </form>
                                <div id="side_accordion" class="accordion">
                                    <?php 
                                        // rendering the menu
                                        foreach($admin_menu as $main_menu => $main_menu_data){
                                            $menu_items = array();
                                            $menu_plugins = array();
                                            $plugin_items = array();
                                            
                                            $menu_items[] = $main_menu;
                                            
                                            if (count($main_menu_data['submenus'])){
                                                foreach($main_menu_data['submenus'] as $sub_menu => $sub_menu_data){
                                                    if ($sub_menu_data['type'] == "plugin"){
                                                        $plugin_items[] = $sub_menu;
                                                        $menu_plugins[] = $sub_menu_data['plugin'];
                                                    } else {
                                                        $menu_items[] = $sub_menu;
                                                    }
                                                }
                                            }
                                                                                
                                            if ((isset($menu) && in_array($menu,$menu_items)) || (isset($menu) && $menu=="plugin" && isset($plugin) && in_array($plugin,$menu_plugins) && isset($plugin_menu) && in_array($plugin_menu,$plugin_items))){
                                                $in = " in";
                                                
                                            } else {
                                                $in = "";
                                            }
                                            
                                            if ($main_menu_data['url']){
                                                $main_target = $main_menu_data['url'];
                                                $main_target_hash = $main_target;
                                                $clickable = " clickable";
                                                $toggle = "";
                                            } else {
                                                
                                                $main_target = "collapse-".$main_menu;
                                                $main_target_hash = "#".$main_target;
                                                $clickable = "";
                                                $toggle = " data-toggle=\"collapse\"";
                                            }
                                    ?>
                                    
                                            <div class="accordion-group">
                                                <div class="accordion-heading">
                                                    <a href="<?php print($main_target_hash); ?>" data-parent="#side_accordion" <?php print($toggle); ?> class="accordion-toggle<?php print($clickable); ?>">
                                                        <i class="<?php print($main_menu_data['icon']); ?>"></i> <?php print($main_menu_data['title']); ?>
                                                    </a>
                                                </div>
                                            
                                    <?php 
                                            if (count($main_menu_data['submenus'])){
                                    ?>                                                 
                                                <div class="accordion-body collapse<?php print($in); ?>" id="<?php print($main_target); ?>">
                                                    <div class="accordion-inner">
                                                        <ul class="nav nav-list">
                                                            <?php 
                                                                foreach($main_menu_data['submenus'] as $sub_menu => $sub_menu_data){
                                                                    if ($sub_menu_data['type'] == "normal"){
                                                                        if (isset($menu) && $menu == $sub_menu){
                                                                            $active = " class=\"active\"";    
                                                                        } else {
                                                                            $active = "";
                                                                        }
                                                                        
                                                                        print("<li{$active}><a href=\"{$sub_menu_data['url']}\">{$sub_menu_data['title']}</a></li>");
                                                                    } elseif ($sub_menu_data['type'] == "plugin"){
                                                                        if (isset($plugin) && $plugin == $sub_menu_data['plugin'] && isset($plugin_menu) && $plugin_menu == $sub_menu){
                                                                            $active = " class=\"active\"";    
                                                                        } else {
                                                                            $active = "";
                                                                        }
                                                                        
                                                                        print("<li{$active}><a href=\"{$sub_menu_data['url']}\">{$sub_menu_data['title']}</a></li>");
                                                                    }
                                                                }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                    <?php
                                            }
                                    ?>
                                            </div>
                                    <?php     
                                        }
                                    
                                    ?>

                                </div>
                                
                                <div class="push"></div>
                                
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div style="padding: 0px 15px 0px 15px; font-size:11px; font-style:italic;">
                                            <strong>Tip of the moment:</strong><br /><br />
                                            <?php 
                                                print($tips[rand(0,count($tips)-1)]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            
            </div>
            
            <script>
                var menu = '<?php print($menu); ?>';
            </script>
            
            <script src="js/jquery.debouncedresize.min.js"></script>
            <!-- hidden elements width/height -->
            <script src="js/jquery.actual.min.js"></script>
            <!-- js cookie plugin -->
            <script src="js/jquery.cookie.min.js"></script>
            <!-- main bootstrap js -->
            <script src="bootstrap/js/bootstrap.js"></script>
            <!-- tooltips -->
            <script src="lib/qtip2/jquery.qtip.min.js"></script>
            <!-- jBreadcrumbs -->
            <script src="lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
            
            <!-- fix for ios orientation change -->
            <script src="js/ios-orientationchange-fix.js"></script>
            <!-- scrollbar -->
            <script src="lib/antiscroll/antiscroll.js"></script>
            <script src="lib/antiscroll/jquery-mousewheel.js"></script>
            <!-- common functions -->
            <script src="js/gebo_common.js"></script>
            
            <script src="lib/jquery-ui/jquery-ui-1.8.20.custom.min.js"></script>
            <!-- touch events for jquery ui-->
            <script src="js/forms/jquery.ui.touch-punch.min.js"></script>
            <!-- multi-column layout -->
            <script src="js/jquery.imagesloaded.min.js"></script>
            <script src="js/jquery.wookmark.js"></script>
            <!-- responsive table -->
            <script src="js/jquery.mediaTable.min.js"></script>
            <!-- small charts -->
            <script src="js/jquery.peity.min.js"></script>

            <script src="lib/fullcalendar/fullcalendar.min.js"></script>
            <!-- sortable/filterable list -->
            <script src="lib/list_js/list.min.js"></script>
            <script src="lib/list_js/plugins/paging/list.paging.min.js"></script>
            
            <script src="lib/colorbox/jquery.colorbox.min.js"></script>
            
            <!-- datatable -->
            <script src="lib/datatables/jquery.dataTables.min.js"></script>
            <script src="lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>
            <script src="lib/datatables/jquery.dataTables.sorting.js"></script>
            
            <?php if (isset($menu) && $menu=="dashboard"){?>
                <script src="lib/flot/jquery.flot.min.js"></script>
                <script src="lib/flot/jquery.flot.resize.min.js"></script>
                <script src="lib/flot/jquery.flot.pie.min.js"></script>
                <script src="js/scripts_dashboard.js"></script>
            <?php } ?>
            
            <?php if (isset($menu) && $menu=="users"){ ?>
                <script src="js/scripts_users.js"></script>
            <?php } ?>
            
            <?php if (isset($menu) && $menu=="users_email"){ ?>
                <script src="js/scripts_users_email.js"></script>
            <?php } ?>
            
            <?php if (isset($menu) && $menu=="settings_accounts"){ ?>
                <script src="js/scripts_settings_accounts.js"></script>
            <?php } ?>
            
            <?php if (isset($menu) && $menu=="shows_manage"){ ?>
                <script src="js/scripts_shows_manage.js"></script>
            <?php } ?>
            
            <?php if (isset($menu) && $menu=="movies_manage"){ ?>
                <script src="js/scripts_movies_manage.js"></script>
            <?php } ?>
            
            <?php if (isset($menu) && $menu=="shows_new"){ ?>
                <script src="lib/ajaxupload/ajaxupload.js"></script>
                <script src="js/scripts_shows_new.js"></script>
            <?php } ?>
            
            <?php if (isset($menu) && $menu=="movies_new"){ ?>
                <script src="lib/ajaxupload/ajaxupload.js"></script>
                <script src="js/scripts_movies_new.js"></script>
            <?php } ?>
            
            <?php if (isset($menu) && $menu=="episodes"){ ?>
                <script src="lib/ajaxupload/ajaxupload.js"></script>
                <script src="js/scripts_episodes.js"></script>
            <?php } ?>

            <?php if (isset($menu) && $menu=="edit_episodes"){ ?>
                <script src="js/scripts_edit_episodes.js"></script>
            <?php } ?>

            <?php if (isset($menu) && $menu=="search"){ ?>
                <script src="js/scripts_search.js"></script>
            <?php } ?>

            <script src="js/bootstrap.plugins.min.js"></script>
            <script src="lib/sticky/sticky.min.js"></script>           
            <script src="js/forms/jquery.inputmask.min.js"></script>
            <script src="js/forms/jquery.autosize.min.js"></script>
            <script src="js/forms/jquery.counter.min.js"></script>
            <script src="lib/datepicker/bootstrap-datepicker.min.js"></script>
            <script src="lib/datepicker/bootstrap-timepicker.min.js"></script>
            <script src="lib/tag_handler/jquery.taghandler.min.js"></script>
            <script src="js/forms/jquery.spinners.min.js"></script>
            <script src="lib/uniform/jquery.uniform.min.js"></script>
            <script src="js/forms/jquery.progressbar.anim.js"></script>
            <script src="lib/multiselect/js/jquery.multi-select.min.js"></script>
            <script src="lib/chosen/chosen.jquery.min.js"></script>
            <script src="lib/tiny_mce/jquery.tinymce.js"></script>
            <script type="text/javascript" src="lib/plupload/js/plupload.full.js"></script>
            <script type="text/javascript" src="lib/plupload/js/jquery.plupload.queue/jquery.plupload.queue.full.js"></script>
            <script src="js/gebo_forms.js"></script>
            <script src="lib/iphone.switch.js"></script>
        
        </div>
    </body>
</html>