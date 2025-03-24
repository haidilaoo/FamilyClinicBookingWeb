

function openReschedule(button) {
  const appointmentId = button.getAttribute('data-appointment-id');

  // Assuming you have a hidden input and form set up in your HTML
  document.getElementById('hidden-appointment-id').value = appointmentId;
  document.getElementById('reschedule-form').submit();
}

function deleteAppt(button) {
  const appointmentId = button.getAttribute('data-appointment-id');

  // Set the hidden input's value to the appointment ID
  document.getElementById('hidden-appointment-id').value = appointmentId;

  // Submit the form to delete the appointment
  document.getElementById('delete-appointment-form').submit();
}





