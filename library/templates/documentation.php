<div class="wrap bsd-icons-admin">

<h2>How to use SVG Icons</h2>

<p>Welcome to the documentation for SVG Icons. We've tried to make this as easy to use as possible.</p>
<p>The initial icon set is provided by <a href="http://icomoon.io" target="_blank">ICOMOON</a></p>

<p>
    <a href="#shortcode">Shortcode Reference</a> |
    <a href="#show_svg">Function Reference</a> |
    <a href="#actions">Action Reference</a> | 
    <a href="#filters">Filter Reference</a> |
    <a href="#additional">More Info</a>
</p>

    <section id="icons">
        <h3 class="section">Icon Packages</h3>

        <ul>
            <li><a href="https://icomoon.io/#preview-free" target="_blank">Icomoon Free</a> (<span class="code">icomoon-free</span>)</li>
            <li><a href="#add-your-own">Add your own!</a></li>
        </ul>
    </section>

    <section id="add-your-own">
        <h3 class="section">Add Your Own SVG Icons</h3>

        <p>Already purchased SVG icons from another company? Great! Just add them to an /svg folder in your active theme's root. You can change the location of these SVGs by using the <a href="#filter-theme_path" class="code">bsd_svg_theme_path</a> filter</p>
    </section>

    <section id="shortcode">
        <h3 class="section">Shortcodes</h3>

        <article>
            <h4>Usage:</h4>
            <pre><code>
    [svg_icon name="" library=""]
            </code></pre>

            <h4>Attributes:</h4>
            <dl>
                <dt>name</dt>
                    <dd>(<em>required</em>) - the icon name that you're including (filename minus .svg)</dd>
                <dt>library</dt> 
                    <dd>(<em>optional</em>) - the library<br/>Default: <span class="code">icomoon-free</span></dd>
            </dl>

            <h4>Examples:</h4>
            <pre><code>
    [svg_icon name="bubble"]
    [svg_icon name="bubble" library="icomoon-free"]
            </code></pre>
        </article>
    </section>

    <section id="functions">
        <h3 class="section">Functions</h3>

        <a id="function-show_svg"></a>
        <article>
            <h3>Function Reference/show_svg_icon</h3>
            
            <h4>Usage:</h4>
            <pre><code>
    &lt;?php show_svg_icon ( $name, $library='icomoon-free', $args=array() ); ?&gt;
            </code></pre>

            <h4>Parameters:</h4>
            <dl>
                <dt>$name</dt>
                    <dd>(string) (<em>required</em>) - the icon name that you're including (filename without .svg)<br/>Default: none</dd>
                <dt>$library</dt> 
                    <dd>(string) (<em>optional</em>) - the library<br/>Default: <span class="code">icomoon-free</span></dd>
                <dt>$args</dt> 
                    <dd>(array) (<em>optional</em>) - coming soon</dd>
            </dl>

            <h4>Examples:</h4>
            <pre><code>
    &lt;?php show_svg_icon ( 'bubble' ); ?&gt;
    &lt;?php show_svg_icon ( 'bubble', 'icomoon-free' ); ?&gt;
            </code></pre>
        </article>
    </section>

    <section id="actions">
        <h3 class="section">Actions</h3>

        <article id="action-pre_svg">
            <h3>Action/bsd_pre_svg</h3>

            <h4>Usage:</h4>
            <pre><code>
    &lt;?php add_action( 'bsd_pre_svg', 'function_name' ); ?&gt;
            </code></pre>

            <h4>Example:</h4>
            <pre><code>
    &lt;?php
        add_action ( 'bsd_pre_svg', 'my_pre_svg_function' );
        function my_pre_svg_function () {
            echo '&lt;div class="svg_wrapper"&gt;';
        } // function
    ?&gt;
            </code></pre>
        </article>

        <article id="action-post_svg">
            <h3>Action/bsd_post_svg</h3>

            <h4>Usage:</h4>
            <pre><code>
    &lt;?php add_action( 'bsd_post_svg', 'function_name' ); ?&gt;
            </code></pre>

            <h4>Example:</h4>
            <pre><code>
    &lt;?php
        add_action ( 'bsd_post_svg', 'my_post_svg_function' );
        function my_post_svg_function () {
            echo '&lt;/div&gt;';
        } // function
    ?&gt;
            </code></pre>
        </article>
    </section>

    <section id="filters">
        <h3 class="section">Filters</h3>

        <article id="filter-theme_path">
            <h3>Filter/bsd_svg_theme_path</h3>

            <p>If you're like most developers and designers, you like to have your file structure clean. If you want to keep your SVG icons somewhere else in your theme other than the theme/svg, this is the filter for you.</p>

            <h4>Parameters:</h4>
            <dl>
                <dt>$path</dt>
                    <?php 
                        $default = get_stylesheet_directory() . '/svg';
                    ?>
                    <dd>
                        (string) (<em>required</em>) - the path to your theme's svg folder<br/>
                        Default: <span class="code"><?php echo $default; ?></span>
                    </dd>
            </dl>

            <h4>Example:</h4>
            <pre><code>
    &lt;?php
        add_filter ( 'bsd_svg_theme_path', 'my_svg_theme_path_function', 10, 1  );
        function my_svg_theme_path_function ( $path ) {
            $path = '/path/to/svg'; // Don't include the trailing slash.
            $path = get_template_directory() . '/path/to/svg'; // Example with get_template_directory()

            return $path;
        } // function
    ?&gt;
            </code></pre>
        </article>

        <article id="filter-default_library">
            <h3>Filter/bsd_svg_default_library</h3>

            <p>By default we use our internal "icomoon-free" library. However, if you want to override the default library with one that you have installed in your theme, change it with this.</p>

            <h4>Parameters:</h4>
            <dl>
                <dt>$library</dt>
                    <dd>
                        (string) (<em>required</em>) - the default svg icon library<br/>
                        Default: <span class="code">icomoon-free</span>
                    </dd>
            </dl>

            <h4>Example:</h4>
            <pre><code>
    &lt;?php
        add_action ( 'bsd_svg_default_library', 'my_svg_default_library_function', 10, 1  );
        function my_svg_default_library_function ( $library ) {
            
            return 'new-library-name'; // the name of the folder in theme/svg
        } // function
    ?&gt;
            </code></pre>
        </article>

        <article id="filter-default_icon">
            <h3>Filter/bsd_svg_default_icon</h3>

            <p>Sometimes you might want a fallback icon if the one selected could not be found. By default, we do not include one, but one can be specified. Program will validate your selection, and if the selection can't be found in the plugin or in your theme's svg directory, it will reset to not showing any fallback icon.</p>

            <h4>Parameters:</h4>
            <dl>
                <dt>$default_icon</dt>
                    <dd>
                        (array|false) (<em>required</em>) - the default icon<br/>
                        Default:
                        <pre><code>
    array (
        'name' => false,
        'library' => true,
    )
                        </code></pre>
                    </dd>
            </dl>

            <h4>Example:</h4>
            <pre><code>
    &lt;?php
        add_action ( 'bsd_svg_default_icon', 'my_default_svg_function', 10, 1 );
        function my_default_svg_function ( $default_icon ) {
            $default_icon['name']   = 'wordpress';

            return $default_icon;
        } // function
    ?&gt;
            </code></pre>
        </article>

        <article id="filter-classes">
            <h3>Filter/bsd_svg_classes</h3>

            <p>This filter is for if you'd like your own custom classes applied to the <span class="code">&lt;svg&gt;</span>.</p>

            <h4>Parameters:</h4>
            <dl>
                <dt>$classes</dt>
                    <dd>
                        (string) - string of classes<br/>
                        Default: <span class="code">bsd-icon icon-{icon-library-and-name} icon-{icon-name}</span>
                    </dd>
            </dl>

            <h4>Example:</h4>
            <pre><code>
    &lt;?php
        add_action ( 'bsd_svg_classes', 'my_svg_classes_function', 10, 1 );
        function my_post_svg_function ( $classes ) {
            $classes .= ' my-extra-class';

            return $classes;
        } // function
    ?&gt;
            </code></pre>
        </article>

    </section>

    <section id="additional">
        <h3 class="section">Additional Functionality</h3>

        <article id="debug_mode">
            <h3>Debug Mode</h3>

            <p>If you're having issues determining if the plugin is working as expected, you can turn on our DEBUG mode by setting this constant in your <span class="code">theme/functions.php</span> file.</p>

            <pre><code>
    define('BSD_DEBUG', true);
            </code></pre>

            <p>This will cause text messages to show up in two locations:</p>

            <ul>
                <li>When no icon is found that matches your request (and no default to fallback on)</li>
                <li>The Shortcode is missing required attributes</li>
            </ul>
        </article>

    </section>
</div>