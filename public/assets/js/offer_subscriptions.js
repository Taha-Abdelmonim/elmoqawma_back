/**
 * Page User List
 */

"use strict";
let image_path = document.documentElement.getAttribute("data-base-url") + "/images/";
// Datatable (jquery)
$(function() {

  var dt_user_table = $(".datatables-users"),
    select2 = $(".select2"),
    userView = baseUrl + "app/user/view/account",
    statusObj = {
      0: { title: "Pending", class: "bg-label-warning" },
      1: { title: "Active", class: "bg-label-success" }
      // 3: { title: 'Inactive', class: 'bg-label-secondary' }
    };

  if (select2.length) {
    var $this = select2;
    $this.wrap("<div class=\"position-relative\"></div>").select2({
      placeholder: "Select Country",
      dropdownParent: $this.parent()
    });
  }
  var url = `/orders/OfferStatusApi/active/${location.pathname.slice(22)}`;
  if (location.pathname.slice(15, 21) != "active") {
    url = `/orders/OfferStatusApi/inactive/${location.pathname.slice(24)}`;
  }
  // Users datatable
  let lang = document.documentElement.dir == "rtl" ? "ar" : "en";
  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      ajax: url, // JSON file to add data
      columns: [
        // columns according to JSON
        { data: "id" },
        { data: "user" },
        { data: "phone" },
        { data: "offer" },
        { data: "price" },
        { data: "status" },
        { data: "photo" },
        { data: "bank_code" },
        { data: "created_at" },
        { data: "action" }
      ],
      columnDefs: [
        {
          // For Checkboxes
          targets: 0,
          orderable: false,
          searchable: false,
          className: "check-all",
          responsivePriority: 3,
          render: function(data, type, full) {
            var $id = full["id"];
            return `<input type="checkbox" data-id="${$id}" name="deleteAll[]" class="delete-all dt-checkboxes form-check-input">`;
          },
          checkboxes: {
            selectAllRender: "<input type=\"checkbox\" class=\"form-check-input\">"
          }
        },

        {
          // User full name and email
          targets: 1,
          responsivePriority: 4,
          render: function(data, type, full, meta) {
            var user = full["user"],
              $name = user["name_" + lang],
              $email = user["email"];
            // Creates full output for row
            var $row_output =
              "<div class=\"d-flex justify-content-start align-items-center user-name\">" +
              "<div class=\"d-flex flex-column\">" +
              "<a href=\"/user/" + user["id"] + "\" class=\"text-body text-truncate\"><span class=\"fw-semibold\">" +
              $name +
              "</span></a>" +
              "<p class=\"text-muted\">" +
              $email +
              "</p>" +
              "</div>" +
              "</div>";
            return $row_output;
          }
        },
        {
          targets: 2,
          render: function(data, type, full, meta) {
            var phone = full["user"]["phone"];
            return `<a href="https://api.whatsapp.com/send?phone=${phone}" target="_blank" class='text-truncate d-flex align-items-center'>${phone}</a>`;
          }
        },
        {
          targets: 3,
          render: function(data, type, full, meta) {
            var offer = full["offer"];

            return `<a href='/offer/${offer["id"]}' class='text-truncate d-flex align-items-center'>${offer[`name_${lang}`]}</a>`;
          }
        },
        {
          // User Status
          targets: 4,
          render: function(data, type, full, meta) {
            var price = full["price"];
            return ("<span class=\"badge\">" + price + "</span>");
          }
        },
        {
          targets: 5,
          render: function(data, type, full, meta) {
            var $status = full["status"];
            var $id = full["id"];

            // return "<span class='text-truncate d-flex  align-items-center badge " + statusObj[$status].class + '\'>'  +  statusObj[$status].title + '</span>';
            return `
              <label class="switch cursor-pointer">
                <input type="checkbox" class="switch-input" onchange="toggle_active_order(this, ${$id})"  ${
              $status == 1 ? "checked" : ""
            } />
                <span class="switch-toggle-slider">
                  <span class="switch-on">
                    <i class="ti ti-check"></i>
                  </span>
                  <span class="switch-off">
                    <i class="ti ti-x"></i>
                  </span>
                </span>
              </label>
            `;
          }
        },
        {
          // User Status
          targets: 6,
          render: function(data, type, full, meta) {
            var $photo = full["photo"] == null || full["photo"] == "" ? "لا يوجد صوره" : full["photo"];
            if (imageExists(`${image_path}${$photo}`)) {
              $photo = `global/not_found.png`;
            }
            let element;
            element = `
                <a href="${image_path}${$photo}" target="_blank" class="avatar avatar-xl d-block ">
                    <img src="${image_path}${$photo}" alt="Avatar" class="rounded-circle object-cover">
                </a>
              `;
            return (
              element
            );
          }
        },

        {
          targets: 7,
          render: function(data, type, full, meta) {
            var bank_code = full["bank_code"];
            return "<span class='text-truncate d-flex align-items-center'>" + bank_code + "</span>";
          }
        },
        {
          targets: 8,
          render: function(data, type, full, meta) {
            var created_at = new Date(full["created_at"]).toISOString();
            if (lang == "ar") {
              created_at = created_at.slice(11, 19) + " " + created_at.slice(0, 10);
            } else {
              created_at = created_at.slice(0, 10) + " " + created_at.slice(11, 19);
            }
            return `<span class='text-truncate d-flex align-items-center'>${created_at}</span>`;
          }
        },
        {
          // Actions
          targets: -1,
          title: lang == "ar" ? "الإجراءات" : "Actions",
          searchable: false,
          orderable: false,
          render: function(data, type, full, meta) {
            let parent = `
              <div class="d-flex align-items-center">
<!--                <a href="/order/${full.id}" class="text-body"><i class="ti ti-eye ti-sm me-2"></i></a>-->
                <a href="/orders/offer/edit/${full.id}" class="text-body ms-1"><i class="ti ti-edit ti-sm me-2"></i></a>
                <a onclick="return confirm('هل انت متأكد')" href="/orders/offer/delete/${full.id}" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
              </div>
            `;
            return parent;
          }
        }
      ],
      order: [[0, "desc"]],
      dom: "<\"card-header flex-column flex-md-row\"<\"head-label text-center\"><\"dt-action-buttons text-end pt-3 pt-md-0\"B>><\"row\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end\"f>>t<\"row\"<\"col-sm-12 col-md-6\"i><\"col-sm-12 col-md-6\"p>>",
      displayLength: 10,
      language: {
        sLengthMenu: `${lang == "ar" ? "عرض" : "show"} _MENU_`,
        search: `${lang == "ar" ? "بحث..." : "search..."}`,
        searchPlaceholder: `${lang == "ar" ? "بحث..." : "search..."}`
      },
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [
        {
          extend: "collection",
          className: "btn btn-label-primary dropdown-toggle me-2",
          text: "<i class=\"ti ti-file-export me-sm-1\"></i> <span class=\"d-none d-sm-inline-block\">Export</span>",
          buttons: [
            {
              extend: "print",
              text: "<i class=\"ti ti-printer me-1\" ></i>Print",
              className: "dropdown-item",
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                // prevent avatar to be display
                format: {
                  body: function(inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = "";
                    $.each(el, function(index, item) {
                      if (item.classList !== undefined && item.classList.contains("user-name")) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function(win) {
                //customize print view for dark
                $(win.document.body)
                  .css("color", config.colors.headingColor)
                  .css("border-color", config.colors.borderColor)
                  .css("background-color", config.colors.bodyBg);
                $(win.document.body)
                  .find("table")
                  .addClass("compact")
                  .css("color", "inherit")
                  .css("border-color", "inherit")
                  .css("background-color", "inherit");
              }
            },
            {
              extend: "csv",
              text: "<i class=\"ti ti-file-text me-1\" ></i>Csv",
              className: "dropdown-item",
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                // prevent avatar to be display
                format: {
                  body: function(inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = "";
                    $.each(el, function(index, item) {
                      if (item.classList !== undefined && item.classList.contains("user-name")) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: "excel",
              text: "<i class=\"ti ti-file-spreadsheet me-1\"></i>Excel",
              className: "dropdown-item",
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                // prevent avatar to be display
                format: {
                  body: function(inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = "";
                    $.each(el, function(index, item) {
                      if (item.classList !== undefined && item.classList.contains("user-name")) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: "pdf",
              text: "<i class=\"ti ti-file-description me-1\"></i>Pdf",
              className: "dropdown-item",
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                // prevent avatar to be display
                format: {
                  body: function(inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = "";
                    $.each(el, function(index, item) {
                      if (item.classList !== undefined && item.classList.contains("user-name")) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: "copy",
              text: "<i class=\"ti ti-copy me-1\" ></i>Copy",
              className: "dropdown-item",
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                // prevent avatar to be display
                format: {
                  body: function(inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = "";
                    $.each(el, function(index, item) {
                      if (item.classList !== undefined && item.classList.contains("user-name")) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        }
      ]
    });
    $("div.head-label").html(`<h5 class="card-title mb-0">${lang == "ar" ? "بحث في جميع الحقول ..." : "search in all fields"}</h5>`);
  }
});
