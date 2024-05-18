"use strict";
let image_path = document.documentElement.getAttribute("data-base-url") + "/images/";
// Datatable (jquery)
$(function() {

  // Variable declaration for table
  var dt_user_table = $(".datatables-basic"),
    select2 = $(".select2"),
    statusObj = {
      0: { class: "bg-label-warning" },
      1: { class: "bg-label-success" }
      // 3: { title: 'Inactive', class: 'bg-label-secondary' }
    };

  if (select2.length) {
    var $this = select2;
    $this.wrap("<div class=\"position-relative\"></div>").select2({
      placeholder: "Select Country",
      dropdownParent: $this.parent()
    });
  }

  // Users datatable
  // console.log('/getUsersApi')
  let lang = document.documentElement.dir == "rtl" ? "ar" : "en";

  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      ajax: "/getUsersApi", // JSON file to add data
      columns: [
        // columns according to JSON
        { data: "id" },
        { data: `name_${lang}` },
        { data: "phone" },
        { data: "roles_name" },
        { data: "status" },
        { data: "photo" },
        { data: "teacher" },
        { data: "created_at" },
        { data: "action" },
        { data: "" }
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
          // User Status
          targets: 1,
          render: function(data, type, full, meta) {
            var $id = full["id"];

            return (
              "<span class=\"badge\">" + $id + "</span>"
            );
          }
        },
        {
          // User full name and email
          targets: 2,
          responsivePriority: 4,
          render: function(data, type, full, meta) {
            var name = full[`name_${lang}`],
              $email = full["email"],
              $image = full["photo"];
            if ($image) {
              // For photo image
              var $output = `
                <a href="${image_path}${$image}" target="_blank" class="avatar avatar-xs d-block ">
                    <img src="${image_path}${$image}" alt="Avatar" class="rounded-circle object-cover">
                </a>
              `;
            } else {
              // For photo badge
              var stateNum = Math.floor(Math.random() * 6);
              var states = ["success", "danger", "warning", "info", "primary", "secondary"];
              var $state = states[stateNum],
                name = full[`name_${lang}`],
                $initials = name.match(/\b\w/g) || [];
              $initials = (($initials.shift() || "") + ($initials.pop() || "")).toUpperCase();
              $output = "<span class=\"photo-initial rounded-circle bg-label-" + $state + "\">" + $initials + "</span>";
            }
            // Creates full output for row
            var $row_output =
              "<div class=\"d-flex justify-content-start align-items-center user-name\">" +
              "<div class=\"photo-wrapper\">" +
              "<div class=\"photo photo-sm me-3\">" +
              $output +
              "</div>" +
              "</div>" +
              "<div class=\"d-flex flex-column\">" +
              "<a href=\"#" +
              "\" class=\"text-body text-truncate\"><span class=\"fw-semibold\">" +
              name +
              "</span></a>" +
              "<small class=\"text-muted\">" +
              $email +
              "</small>" +
              "</div>" +
              "</div>";
            return $row_output;
          }
        },
        {
          // User Role
          targets: 3,
          render: function(data, type, full, meta) {
            var $phone = full["phone"];

            return "<span class='text-truncate d-flex align-items-center'>" + $phone + "</span>";
          }
        },
        {
          // User Role
          targets: 4,
          render: function(data, type, full, meta) {
            var role = full["roles_name"];
            var roleBadgeObj = {
              "": "<span class=\"badge badge-center rounded-pill bg-label-warning w-px-30 h-px-30 me-2\"><i class=\"ti ti-user ti-sm\"></i></span> user",
              owner: "<span class=\"badge badge-center rounded-pill bg-label-success w-px-30 h-px-30 me-2\"><i class=\"ti ti-circle-check ti-sm\"></i></span>",
              partner: "<span class=\"badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30 me-2\"><i class=\"ti ti-chart-pie-2 ti-sm\"></i></span>",
              editor: "<span class=\"badge badge-center rounded-pill bg-label-info w-px-30 h-px-30 me-2\"><i class=\"ti ti-edit ti-sm\"></i></span>",
              undefined: "<span class=\"badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 me-2\"><i class=\"ti ti-device-laptop ti-sm\"></i></span>"
            };
            return `<span class="text-truncate d-flex align-items-center">${roleBadgeObj[role] ?? roleBadgeObj["undefined"]} ${role}</span>`;
          }
        },
        {
          // User Role
          targets: 5,
          render: function(data, type, full, meta) {
            var balance = full["balance"];

            return `<span class="text-truncate d-flex  align-items-center badge ${balance <= 0 ? statusObj[0].class : statusObj[1].class}"> ${balance} جنية </span>`;
          }
        },
        {
          // User Status
          targets: 6,
          render: function(data, type, full, meta) {
            var $photo = full["photo"] == null || full["photo"] === "" ? "--" : full["photo"];
            let element;
            if (full["photo"] == null || full["photo"] === "") {
              element = "<span class=\"badge\">" + $photo + "</span>";
            } else {
              element = `
                <a href="${image_path}${$photo}" target="_blank" class="avatar avatar-xl d-block ">
                    <img src="${image_path}${$photo}" alt="Avatar" class="rounded-circle object-cover">
                </a>
              `;
            }
            return (
              element
            );
          }
        },
        {
          targets: 7,
          render: function(data, type, full, meta) {
            var teacher = full["teacher"];
            let content = teacher == null ? "--" : `<a href="/teacher/edit/${teacher["id"]}" class="text-truncate d-flex align-items-center">${teacher[`name_${lang}`]}</a>`;
            return content;
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
            return `<span class="text-truncate d-flex align-items-center">${created_at}</span>`;
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
                <a href="javascript:;" data-id="${full.id}"  data-bs-toggle="modal" data-bs-target="#wallet" class="text-body"><i class="fa-solid fa-money-bill-transfer fa-xl mt-3 me-3"></i></a>
                <a href="/user/${full.id}" class="text-body"><i class="ti ti-eye ti-sm me-3"></i></a>
                <a href="/user/edit/${full.id}" class="text-body"><i class="ti ti-edit ti-sm me-2"></i></a>
                <a href="/user/delete/${full.id}" onclick="return confirm('Are you sure?')"  class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
                
              </div>
            `;
            return parent;
          }
        }
      ],
      order: [
        [1, "desc"]
      ],
      dom: "<\"card-header flex-column flex-md-row\"<\"head-label text-center\"><\"dt-action-buttons text-end pt-3 pt-md-0\"B>><\"row\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end\"f>>t<\"row\"<\"col-sm-12 col-md-6\"i><\"col-sm-12 col-md-6\"p>>",
      language: {
        sLengthMenu: `${lang == "ar" ? "عرض" : "show"} _MENU_`,
        search: `${lang == "ar" ? "بحث..." : "search..."}`,
        searchPlaceholder: `${lang == "ar" ? "بحث..." : "search..."}`
      },
      // Buttons with Dropdown
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
