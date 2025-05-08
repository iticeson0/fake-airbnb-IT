// javascript goes here
$(document).ready(function() {
  $('.viewListing').on('click', function() {
    const listingId = $(this).attr('id');

    $.get('src/ajax.php', { id: listingId }, function(data) {
      if (data.error) {
        alert(data.error);
        return;
      }

      const modal = $('#modal' + listingId);
      const imgUrl = data.image_url || 'https://via.placeholder.com/600x400?text=No+Image';

      modal.find('.modal-title').text(data.name);
      modal.find('.modal-body img').attr('src', imgUrl);
      modal.find('.modal-footer').html(`
        <p>${data.neighborhood} neighborhood</p>
        <p>$${parseFloat(data.price).toFixed(2)} / night</p>
        <p>Accommodates ${data.accommodates}</p>
        <p><i class="bi bi-star-fill"></i> ${data.rating}</p>
        <p>Hosted by ${data.host}</p>
        <p>Amenities: ${data.amenities}</p>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      `);
    }, 'json');
  });
});