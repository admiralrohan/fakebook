<?php
/**
 * Takes an array and print the number of elements in useful manner in the view.
 * e.g No like yet / 1 like / 3 likes
 *
 * @param array $arr The array to count
 * @param string $text_in_middle Text to put in the middle
 * @param boolean $use_yet Whether append "yet" at the end of the string
 * @return string
 */
function print_array_count(array $arr, string $text_in_middle, bool $use_yet = true): string {
    return (count($arr) === 0 ? 'No' : count($arr)) . " {$text_in_middle}" . (count($arr) > 1 ? 's' : '') . ($use_yet ? (count($arr) === 0 ? ' yet' : '') : '');
}

function get_date_for_database(string $date): string {
    $timestamp = strtotime($date);
    $date_formated = date('Y-m-d H:i:s', $timestamp);
    return $date_formated;
}

function print_html($post) {
    return
    ?>
    <div>
        <a href="post_liked_by.php?id=<?= $post->id ?>">
            <span class="text-secondary">
                Hello world $post->content
            </span>
        </a>
    </div>
    <?php
    ;
} ?>