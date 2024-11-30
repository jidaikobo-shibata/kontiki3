$(document).ready(function() {

	/**
	 * Event handler for confirmation dialogs on links with data-confirm attributes.
	 * Displays a custom confirmation message based on the type of confirmation.
	 *
	 * @event click
	 * @param {Event} event - The event object for the click action.
	 */
	$(document).on('click', 'a[data-confirm]', function (event) {
		const confirmType = $(this).data('confirm');
		let message;

		if (confirmType === 'critical') {
			message = '取り消すことのできない行為です。本当に実行していいですか？';
		} else {
			message = '実行してもいいですか？';
		}

		const isConfirmed = confirm(message);

		if (!isConfirmed) {
			event.preventDefault(); // デフォルト動作を防止
			event.stopImmediatePropagation(); // 他のリスナーの実行を防止
		}
	});

});
