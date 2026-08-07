<?php $pager->setSurroundCount(2) ?>
<nav aria-label="Page navigation">
    <ul class="flex items-center justify-center gap-1 sm:gap-2">
        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getPrevious() ?>" class="ajax-pagination flex h-9 w-9 items-center justify-center rounded-lg border border-stone-200 bg-white text-sm font-medium text-stone-600 transition hover:bg-red-50 hover:text-red-600 sm:h-10 sm:w-10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" class="ajax-pagination flex h-9 w-9 items-center justify-center rounded-lg border text-sm font-medium transition sm:h-10 sm:w-10 <?= $link['active'] ? 'border-red-600 bg-red-600 text-white shadow-sm' : 'border-stone-200 bg-white text-stone-600 hover:bg-red-50 hover:text-red-600' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" class="ajax-pagination flex h-9 w-9 items-center justify-center rounded-lg border border-stone-200 bg-white text-sm font-medium text-stone-600 transition hover:bg-red-50 hover:text-red-600 sm:h-10 sm:w-10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>