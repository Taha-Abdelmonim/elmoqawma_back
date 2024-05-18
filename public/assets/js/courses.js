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

  // Users datatable
  let lang = document.documentElement.dir == "rtl" ? "ar" : "en";
  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      ajax: "/getCourses", // JSON file to add data
      columns: [
        // columns according to JSON
        { data: "id" },
        { data: `name` },
        { data: `subject` },
        { data: "section" },
        { data: "teacher" },
        { data: "status" },
        { data: "price" },
        { data: "created_at" },
        { data: "action" }
      ],
      columnDefs: [
        {
          // User Status
          targets: 0,
          render: function(data, type, full, meta) {
            var $id = full["id"];

            return (
              "<span class=\"badge\">" + $id + "</span>"
            );
          }
        },
        {
          targets: 1,
          render: function(data, type, full, meta) {
            var name = full["name"];
            let content = name == null || name === "" ? "--" : `<p class="text-truncate d-flex align-items-center">${name}</p>`;
            return content;
          }
        },
        {
          targets: 2,
          render: function(data, type, full, meta) {
            var subject = full["subject"];
            let content = subject == null ? "--" : `<a href="/subject/edit/${subject["id"]}" class="text-truncate d-flex align-items-center">${subject[`name_${lang}`]}</a>`;
            return content;
          }
        },
        {
          targets: 3,
          render: function(data, type, full, meta) {
            var section = full["section"];
            let content = section == null ? "--" : `<a href="/sections?section_id=${section["id"]}" class="text-truncate d-flex align-items-center">${section[`name_${lang}`]}</a>`;
            return content;
          }
        },
        {
          targets: 4,
          render: function(data, type, full, meta) {
            var teacher = full["teacher"];
            let content = teacher == null ? "--" : `<a href="/teacher/edit/${teacher["id"]}" class="text-truncate d-flex align-items-center">${teacher[`name_${lang}`]}</a>`;
            return content;
          }
        },
        {
          targets: 5,
          render: function(data, type, full, meta) {
            var status = full["status"];
            return "<span class='text-truncate d-flex  align-items-center badge " + statusObj[status].class + "'>" + statusObj[status].title + "</span>";
          }
        },
        {
          targets: 6,
          render: function(data, type, full, meta) {
            var price = full["price"];
            return "<span class='text-truncate d-flex align-items-center'>" + price + "</span>";
          }
        },
        {
          targets: 7,
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
                <a href="/course/${full.id}/chapters" class="text-body ms-1"><i class="fa-solid fa-chart-pie fa-xl me-3"></i></a>
                <a href="/comments/courses?course=${full.id}" class="text-body"><i class="fa-solid fa-comments fs-4 me-3 mt-1"></i></a>
                <a href="/course/${full.id}" class="text-body"><i class="ti ti-eye ti-sm me-2"></i></a>
                <a href="/course/edit/${full.id}" class="text-body ms-1"><i class="ti ti-edit ti-sm me-2"></i></a>
                <a onclick="return confirm('هل انت متأكد')" href="/course/delete/${full.id}" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
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
                columns: [0, 1, 2, 3, 4, 6, 7],
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
                columns: [0, 1, 2, 3, 4, 6, 7],
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
                columns: [0, 1, 2, 3, 4, 6, 7],
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
                columns: [0, 1, 2, 3, 4, 6, 7],
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
                columns: [0, 1, 2, 3, 4, 6, 7],
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
