<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1">

    <title>Categories</title>
  </head>

  <body>
    <div class="">
      <h1 class="text-gray-900 font-semibold text-xl mx-10 mt-3">Categories menu</h1>

      <ul id="menu" class="mt-4 mx-10 border-l-2 border-gray-400 pl-2 w-80">
      </ul>
    </div>


  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script>
    $(document).ready( function () {
        axios.get('/api/root-categories')
            .then( response => {
                let data = response.data;

                for (let i = 0; i < data.length; i++) {
                    $('#menu').append(`<li data-id="${data[i].id}" ` +
                        "data-show-toggle=true " +
                        "class=\'categoryClass border border-gray-400 my-3 p-2 w-72 rounded-lg hover:cursor-pointer hover:bg-gray-300\'>" +
                        `<div class='flex justify-between'>${data[i].name}<span class="text-gray-500">(${data[i].count})</span></div></li>`);
                }
            })

        $('#menu').on( "click", '.categoryClass', function () {
            let categoryId = $(this).data('id');

            if ($(this).data('show-toggle') === true) {
                axios.get(`/api/${categoryId}/subcategories`)
                    .then( response => {
                        let data = response.data;

                        if (data.length > 0) {
                            $(`[data-id=${categoryId}]`).after(`<ul id="submenu-${categoryId}" class="subcategoryClass mt-4 mx-10 border-l-2 border-gray-400 pl-2 w-72"></ul>`);

                            for (let i = 0; i < data.length; i++) {
                                $(`#submenu-${categoryId}`).append(`<li data-id="${data[i].id}" ` +
                                    "data-show-toggle=true " +
                                    "class=\'categoryClass border border-gray-400 my-3 p-2 w-72 rounded-lg hover:cursor-pointer hover:bg-gray-300\'>" +
                                    `<div class='flex justify-between'>${data[i].name}<span class="text-gray-500">(${data[i].count})</span></div></li>`);
                            }

                            $(this).css('background-color', '#d1d5db')

                        } else {
                            alert('No subcategories to show.')
                        }
                    })
            } else {
                $(`#submenu-${categoryId}`).remove();

                $(this).css('background-color', 'white')
            }

            $(this).data('show-toggle', $(this).data('show-toggle') !== true);
        });
    });
  </script>
  </body>
</html>
