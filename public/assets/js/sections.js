/**
 * DataTables Basic
 */

"use strict";

/*{
    targets: 1,
        searchable: false,
    visible: false
},
{
                    // Label
                    targets: -2,
                    render: function (data, type, full, meta) {
                        var $status_number = full['status'];
                        var $status = {
                            1: { title: 'Current', class: 'bg-label-primary' },
                            2: { title: 'Professional', class: ' bg-label-success' },
                            3: { title: 'Rejected', class: ' bg-label-danger' },
                            4: { title: 'Resigned', class: ' bg-label-warning' },
                            5: { title: 'Applied', class: ' bg-label-info' }
                        };
                        if (typeof $status[$status_number] === 'undefined') {
                            return data;
                        }
                        return (
                            '<span class="badge ' + $status[$status_number].class + '">' + $status[$status_number].title + '</span>'
                        );
                    }
                },
*/
// datatable (jquery)
$(function() {
  let image_path =
    document.documentElement.getAttribute("data-base-url") + "/images/";
  var dt_basic_table = $(".datatables-basic"),
    dt_basic;
  let lang = document.documentElement.dir == "rtl" ? "ar" : "en";
  if (dt_basic_table.length) {
    var urlSection = "getSections";
    if (location.search.slice(1, 11) == "section_id") {
      urlSection = `getSections${location.search}`;
    }
    dt_basic = dt_basic_table.DataTable({
      ajax: urlSection,
      columns: [
        { data: "id" },
        { data: `name_${lang}` },
        { data: `photo` },
        { data: `section` },
        { data: "created_at" },
        { data: "actions" },
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
          targets: 1,
          render: function(data, type, full, meta) {
            var id = full[`id`];
            return `<span>${id}</span>`;
          }
        },
        {
          targets: 2,
          render: function(data, type, full, meta) {
            var name = full[`name_${lang}`];
            var id = full[`id`];
            return `<a href="/sections?section_id=${id}">${name}</a>`;
          }
        },
        {
          targets: 3,
          render: function(data, type, full, meta) {
            var $photo = full["photo"] == null || full["photo"] == "" ? "--" : full["photo"];
            return (
              `
                <a href="${image_path}${$photo}" target="_blank" class="avatar avatar-xl d-block ">
                    <img src="${image_path}${$photo}" alt="Avatar" class="rounded-circle object-cover">
                </a>
              `
            );
          }
        },
        {
          targets: 4,
          render: function(data, type, full, meta) {
            var section = full[`section`];
            return `<span>${section}</span>`;
          }
        },
        {
          targets: 5,
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
          orderable: false,
          searchable: false,
          render: function(data, type, full, meta) {
            let parent = `
                <div class="d-flex align-items-center">
                  <a href="/section/edit/${full.id}" class="text-body"><i class="ti ti-edit ti-sm me-2"></i></a>
                  <a onclick="return confirm('Are you sure?')" href="/section/delete/${full.id}" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
                  
                </div>
              `;
            return parent;
          }
        }
      ],
      order: [[1, "asc"]],
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
