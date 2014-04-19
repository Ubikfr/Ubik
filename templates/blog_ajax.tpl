        <div  class="container">
{foreach $posts as $post}
            <article class="post">
                <header>
                    <h1 class="post-title">{$post.titre}</h1>
                    <p class="post-meta">
                        <a title="permalien" class="icon-link" href="/blog/{$post.slug}">Permalien &nbsp;</a>
                        <time datetime="{$post.date|date_canonical}">{$post.date|date_human}</time>
                    </p>
                    <ul class="tags">
{foreach $post.tag|split_tags as $one_tag}
                        <li><a href="/blog/tag/{$one_tag}">{$one_tag|print_tag}</a></li>
{/foreach}
                    </ul>
                </header>
                <section class="post-content">
                    {$post.content}
                </section>
            <hr class="split">
            </article>
{/foreach}
        </div>

