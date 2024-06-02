$(document).ready(function () {
    const HOURS_OF_DAY = Object.freeze(['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00']);

    fetchClasses();
    setInputToCurrentDate();

    function fetchClasses() {
        $.ajax({
            url: 'class.php',
            method: 'GET',
            contentType: 'application/json',
            success: function (response) {
                response.forEach(classObj => {
                    const option = $('<option>')
                        .text(classObj.name)
                        .val(classObj.id)
                        .on('click', () => fetchClassAttendance(classObj.id));
                    $('#class').append(option);
                });
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function fetchClassAttendance(classId) {
        $.ajax({
            url: 'class_attendance.php',
            method: 'GET',
            contentType: 'application/json',
            data: { classId: classId },
            success: function (response) {
                renderClassAttendance(response, classId);
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function renderClassAttendance(classAttendance, classId) {
        $('#client-attendance-table-body').empty();

        if (classAttendance.length === 0) {
            $('#client-attendance-table-body').append(
                $('<tr>').append(
                    $('<td>')
                        .attr('colspan', '15')
                        .addClass('px-4 py-72')
                        .append($('<span>').addClass('flex justify-center').append($('<i>').attr('data-lucide', 'badge-alert'), $('<span>').addClass('ml-2').text('Nenhum horário hoje')))
                )
            );
        }

        classAttendance.forEach(client => {
            const tr = $('<tr>').addClass('border-b border-gray-200');
            const name = $('<td>').addClass('px-4 py-2 text-left').text(client.name);
            tr.append(name);

            HOURS_OF_DAY.forEach(hour => {
                const td = $('<td>').addClass('px-4 py-2 text-left');

                const attendance = client.attendances.find(a => a.hour === hour);
                if (attendance) {
                    const input = $('<input>')
                        .attr('type', 'checkbox')
                        .attr('name', 'attendance')
                        .attr('value', `${attendance.schedule_id}-${client.client_id}-${classId}-${hour}`)
                        .prop('checked', attendance.attendance_status === 1)
                        .on('click', function () {
                            const [schedule_id, client_id, class_id, _] = $(this).val().split('-');
                            const isChecked = $(this).is(':checked');

                            $.ajax({
                                url: 'class_attendance.php',
                                method: isChecked ? 'POST' : 'DELETE',
                                contentType: 'application/json',
                                data: JSON.stringify({
                                    schedule_id: schedule_id,
                                    client_id: client_id,
                                    class_id: class_id,
                                }),
                                success: function (response) {
                                    console.log(response);
                                },
                                error: function (error) {
                                    console.log(error);
                                },
                            });
                        });

                    td.append(input);
                } else {
                    const input = $('<input>').attr('type', 'checkbox').attr('name', 'attendance').attr('value', `${client.id}-${hour}`).attr('disabled', 'disabled');
                    td.append(input);
                }

                tr.append(td);
            });

            $('#client-attendance-table-body').append(tr);
        });

        lucide.createIcons();
    }

    function setInputToCurrentDate() {
        const date = new Date();
        const month = date.getMonth() + 1;
        const day = date.getDate();
        const year = date.getFullYear();
        $('#date').val(`${year}-${month < 10 ? '0' + month : month}-${day < 10 ? '0' + day : day}`);
    }

    lucide.createIcons();
});
