/**
 * App user list
 */

"use strict";
let image_path = document.documentElement.getAttribute("data-base-url") + "/images/";
// Datatable (jquery)
$(function() {
  var dtUserTable = $(".datatables-users"),
    statusObj = {
      0: { title: "Pending", class: "bg-label-warning" },
      1: { title: "Active", class: "bg-label-success" }
    };

  let lang = document.documentElement.dir == "rtl" ? "ar" : "en";

  // Users List datatable
  if (dtUserTable.length) {
    var dtUser = dtUserTable.DataTable({
      ajax: "/getReviews", // JSON file to add data
      columns: [
        // columns according to JSON
        { data: "" },
        { data: "id" },
        { data: "user" },
        { data: "show" },
        { data: "status" },
        { data: "section" },
        { data: "created_at" },
        { data: "" }
      ],
      columnDefs: [
        {
          // For Responsive
          className: "control",
          orderable: false,
          searchable: false,
          responsivePriority: 2,
          targets: 0,
          render: function(data, type, full, meta) {
            return "";
          }
        },
        {
          // User Status
          targets: 1,
          render: function(data, type, full, meta) {
            var $id = full["id"];

            return "<span class=\"badge\">" + $id + "</span>";
          }
        },
        {
          targets: 2,
          render: function(data, type, full, meta) {
            var $user = full["user"];
            return `<a href="user/${$user.id}" class="text-truncate d-flex align-items-center">${$user["name_" + lang]} </a>`;
          }
        },
        {
          targets: 3,
          render: function(data, type, full, meta) {
            var $review_id = full["id"];
            return `<a href="/review/${$review_id}" class="text-truncate d-flex align-items-center">${lang == "ar" ? "عرض التقييم" : "show review"}</a>`;
          }
        },
        {
          targets: 4,
          render: function(data, type, full, meta) {
            var $status = full["status"];
            var $id = full["id"];

            // return "<span class='text-truncate d-flex  align-items-center badge " + statusObj[$status].class + '\'>'  +  statusObj[$status].title + '</span>';
            return `
              <label class="switch cursor-pointer">
                <input type="checkbox" class="switch-input" onchange="toggle_active_comment(this, ${$id})"  ${
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
          targets: 5,
          render: function(data, type, full, meta) {
            var section = full["section"];

            return `<a href="/sections?section_id=${section["id"]}" class="text-truncate d-flex align-items-center">${section[`name_${lang}`]}</a>`;
          }
        },
        {
          targets: 6,
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
                 <a href="/review/${full.id}" class="text-body"><i class="ti ti-eye ti-sm me-2"></i></a>
                 <a onclick="return confirm('هل انت متأكد')" href="/review/delete/${full.id}" class="text-bod   y"><i class="ti ti-trash ti-sm mx-2"></i></a>
              </div>
            `;
            return parent;
          }
        }
      ],
      order: [[1, "desc"]],
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
                columns: [0, 1, 2, 3, 4, 6],
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
                columns: [0, 1, 2, 3, 4, 6],
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
                columns: [0, 1, 2, 3, 4, 6],
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
                columns: [0, 1, 2, 3, 4, 6],
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
                columns: [0, 1, 2, 3, 4, 6],
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
