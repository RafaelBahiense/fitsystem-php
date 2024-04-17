$(document).ready(() => {
  $.get("list_clients.php", (data) => {
    data.forEach((client) => {
      //$("<li></li>").text(client.name).appendTo("#clients-list");
      $(
        `<li>
          <span>
            ${client.name}
          </span>
          <span>
            ${client.age}
          </span>
          <span>
            ${client.gender}
          </span>
          <span>
            <button >
              delete
            <button/>
          </span>
        </li>`,
      ).appendTo("#clients-list");
    });
  });
  $("#add-client").click(() => {
    const client = {
      name: $("#name").val(),
      age: $("#age").val(),
      gender: $("#gender").val(),
      phone: $("#phone").val(),
      address: $("#address").val(),
    };
    $.post("add_client.php", client, (_) => {
      $(
        `<li>
          <span>
            ${client.name}
          </span>
          <span>
            ${client.age}
          </span>
          <span>
            ${client.gender}
          </span>
          <span>
            <button >
              delete
            <button/>
          </span>
        </li>`,
      ).appendTo("#clients-list");
    });

    $("#name").val("");
    $("#age").val("");
    $("#gender").val("");
    $("#phone").val("");
    $("#address").val("");
  });
});
