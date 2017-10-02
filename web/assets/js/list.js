$(document).ready(function () {
    const list = (function () {
        const $paginationLinks = $('.pagination');

        const getLessons = function (categorySlug, page) {
            return $.ajax({
                url: App.ajaxListUrl,
                data: 'categorySlug='+categorySlug+'&page='+page,
                method: 'get'
            })
        };

        const paginationHandler = function ($el, categorySlug, lessonList) {
            const $lessonListWrapper = $('.js-lesson-list-wrapper[data-category-slug="'+categorySlug+'"]');
            const $pagination = $('.pagination a[data-category-slug="'+categorySlug+'"]');

            $lessonListWrapper.children().hide();

            if (lessonList) {
                $lessonListWrapper.append(lessonList);
            }


            $pagination.parent().removeClass('active');
            $el.parent().addClass('active');
        };

        const paginationInit = function () {
            $paginationLinks.on('click', 'a', function (e) {
                e.preventDefault();
                const $self = $(this);
                const categorySlug = $self.attr('data-category-slug');
                const page = $self.html();

                const $currentList = $('.js-lesson-list[data-category-slug="'+categorySlug+'"][data-page="'+page+'"]');
                if ($currentList.length === 0) {
                    getLessons(categorySlug, page)
                        .done(function (lessonList) {
                            paginationHandler($self, categorySlug, lessonList);
                        });
                } else {
                    paginationHandler($self, categorySlug);
                    $currentList.show();
                }
            });
        };

        paginationInit();
    })();
});