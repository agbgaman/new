$(document).ready(function () {
	"use strict";
    init_multiselect();

	$('#country').select2();

	$(document).on('change', '#country', function (e) {
		e.preventDefault();
		var country = $('#country').val();
		var formData = new FormData();
		formData.append("country", country);

		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			method: 'post',
			url: '/admin/notifications/get-city-users',
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				if (data.result == 'success') {
                    $(".select_users").html(data.options);
                    init_multiselect();
				}
			},
			error: function (data) {
				Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
			}
		});
	});

	$(document).on('change', '#city', function (e) {
		e.preventDefault();
		var city = $('#city').val();
		var formData = new FormData();
		formData.append("city", city);

		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			method: 'post',
			url: '/admin/notifications/get-city-users',
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				if (data.result == 'success') {
					$(".select_users").html(data.options);

					init_multiselect();
				}
			},
			error: function (data) {
				Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
			}
		});
	});

	$(document).on('keyup', '.multiselect-search', function (e) {
		e.preventDefault();
		var city = $('#city').val();
		var search = $('.multiselect-search').val();
		var formData = new FormData();
		formData.append("city", city);
		formData.append("search", search);

		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			method: 'post',
			url: '/admin/notifications/get-city-users',
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				if (data.result == 'success') {
					$('#users').multiselect('destroy');
					$('#users').html(data.opt);
					init_multiselect();

					$('.multiselect-search').val(search);
					$('.select_users .btn-group .dropdown-toggle').trigger('click');
					$('.select_users .multiselect-search').focus();
				}
			},
			error: function (data) {
				Swal.fire({ type: 'error', title: 'Oops...', text: 'Something went wrong!' })
			}
		});
	});
});

function init_multiselect() {
	$('#users').multiselect({
		includeSelectAllOption: true,
		filterPlaceholder: 'Search',
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
		enableFullValueFiltering: true,
		includeFilterClearBtn: false,
	});

}
