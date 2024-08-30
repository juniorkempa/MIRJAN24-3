$(document).ready(function() {
    $.ajax({
        url: firstmodule_ajax_url,
        type: 'POST',
        dataType: 'json',
        data: { 
            ajax: true 
        },
        success: function(response) {
            if (response.success) {
                let content = response.data.map(product => 
                    `<div class="glide">
                         <div class="glide__track" data-glide-el="track">
                            <ul class="glide__slides">
                                <li class="glide__slide">${product.name} <br>    
                                Price: ${product.price}<br>
                                    ${product.images.map(image => 
                                        `<img src="${image}" style="width:100px; height:auto;">`
                                    ).join('')}
                                </li>
                            </ul>
                        </div>
                    </div>`
                ).join('');

                $('#ajaxx').html(content);

                // new Glider(document.querySelector('.glide'), {
                //     slidesToShow: 1,
                //     arrows: {             <-------- NIE DZIALA
                //         prev: '.glider-prev',
                //         next: '.glider-next'
                //     }    
                // });
                
            } else {
                console.log('Błąd:', response.message);
            }
        },
        error: function() {
            console.error('ERROR: ', response.message);
        }
    });
});