<?php 
if (!defined("IN_SCRIPT") || !isset($_SESSION['admin_user_id']) || !$_SESSION['admin_user_id'] || !isset($_SESSION['admin_username']) || !$_SESSION['admin_username']){
    header("Location: login.php?menu=$menu");
    die();
}

if (!isset($query) || !$query){
    print("<script> window.location = 'index.php'; </script>");
    exit();
}

?>

<nav>
    <div id="jCrumbs" class="breadCrumb module">
        <ul>
            <li>
                <a href="index.php"><i class="icon-home"></i></a>
            </li>
            <li>
                Search
            </li>
        </ul>
    </div>
</nav>

<div class="row-fluid">
    <div class="span12">
        <div class="heading clearfix">
            <h3 class="pull-left">TV show results for "<?php print($query); ?>"</h3>
            <form action="index.php" class="input-append pull-right" method="post" >
                <input type="hidden" name="menu" value="search" />
                <button type="submit" class="btn pull-right"  style="margin:0px;"><i class="icon-search"></i></button>
                <input autocomplete="off" name="query" class="input-medium pull-right" value="<?php print($query); ?>" style="margin:0px;" size="16" type="text" placeholder="Search..." />
                
            </form>
        </div>
        
        
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="1">&nbsp;</th>
                    <th width="40%">Title</th>
                    <th width="*">Description</th>
                    <th width="1">&nbsp;</th>
                </tr>
            </thead>
            <tbody>    
                <?php 
                    $tv_results = $show->search($query, $default_language);
                    if (!count($tv_results)){
                        print("<tr><td colspan='4'>There is no TV show / episode matching your search query</td></tr>");
                    } else {
                        foreach($tv_results as $episode_id => $episode_data){
                            
                            print("    <tr>
                                        <td>
                                            <div class=\"thumbnail\">
                                                <a href=\"$baseurl/thumbs/{$episode_data['thumbnail']}\"><img src=\"$baseurl/thumbs/{$episode_data['thumbnail']}\" style=\"width:60px\" /></a>
                                            </div>
                                        </td>
                                        <td><a href=\"index.php?menu=edit_episodes&showid={$episode_data['show_id']}\">{$episode_data['title']}</a> - Season {$episode_data['season']} Episode {$episode_data['episode']}</td>
                                        <td>{$episode_data['description']}</td>
                                        <td>
                                            <a href=\"index.php?menu=episodes&show_id={$episode_data['show_id']}&season={$episode_data['season']}&episode={$episode_data['episode']}\" class=\"btn\">Edit</a>
                                        </td>
                                    </tr>");
                        }
                    }
                ?>
            </tbody>
        </table>    
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="heading clearfix">
            <h3 class="pull-left">Movie results for "<?php print($query); ?>"</h3>
            <form action="index.php" class="input-append pull-right" method="post" >
                <input type="hidden" name="menu" value="search" />
                <button type="submit" class="btn pull-right"  style="margin:0px;"><i class="icon-search"></i></button>
                <input autocomplete="off" name="query" class="input-medium pull-right" value="<?php print($query); ?>" style="margin:0px;" size="16" type="text" placeholder="Search..." />
                
            </form>
        </div>
        
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="1">Thumbnail</th>
                    <th width="40%">Title</th>
                    <th width="*">Description</th>
                    <th width="1">&nbsp;</th>
                </tr>
            </thead>
            <tbody>    
                <?php 
                    $movie_results = $movie->search($query, $default_language);
                    if (!count($movie_results)){
                        print("<tr><td colspan='4'>There is no movie matching your search query</td></tr>");
                    } else {
                        foreach($movie_results as $movie_id => $movie_data){
                            print("    <tr>
                                        <td>
                                            <div class=\"thumbnail\">
                                                <a href=\"$baseurl/thumbs/{$movie_data['thumb']}\"><img src=\"$baseurl/thumbs/{$movie_data['thumb']}\" style=\"width:60px\" /></a>
                                            </div>
                                        </td>
                                        <td>{$movie_data['title']}</td>
                                        <td>{$movie_data['description']}</td>
                                        <td>
                                            <a href=\"index.php?menu=movies_new&movie_id=$movie_id\" class=\"btn\">Edit</a>
                                        </td>
                                    </tr>");
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>