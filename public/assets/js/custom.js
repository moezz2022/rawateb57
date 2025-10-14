$(function () {
    $(".main-header .dropdown > a").on("click", function (e) {
        e.preventDefault();
        $(this).parent().toggleClass("show");
        $(this).parent().siblings().removeClass("show");
        $(this).find(".drop-flag").removeClass("show");
    });
});

/***********************************************************************/

function togglePasswordVisibility(inputId, iconId) {
    const passwordField = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}

/*****************************************page salarySlip payroll******************************/
$(document).ready(function () {
    $("#printSlipButton").on("click", function () {
        let printContent = document.getElementById("salary-slip").innerHTML;
        let newWindow = window.open("", "_blank");
        newWindow.document.open();
        newWindow.document.write(`
<html dir="rtl">
<head>
    <title>طباعة كشف الراتب</title>
    <style>
        @page {
            size: portrait;
            margin: 0.5cm;
        }
        body {
            direction: rtl;
            font-family: 'Cairo', sans-serif;
            text-align: right;
            color: #1e293b;
            margin-bottom: 120px; /* ترك مساحة للفوتر */
        }
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 8px 0;
            border-top: 1px dashed #ccc;
            background: #fff;
        }
        .barcode-stamp img {
            height: 55px;
            width: auto;
            margin: 0 auto;
        }
        .barcode-stamp .code {
            margin-top: 4px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .footer-pay {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 1px;
            text-align: right;
        }
        th {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        #salary-slip {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
            
  

        .details-table td {
            font-size: 12px;
            text-align: right;
            padding: 4px;
        }
        .text-center {
            text-align: center;
        }
        .highlight {
            font-weight: bold;
            background: #edeff7;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body onload="window.print();">
    ${printContent}
</body>
</html>
`);
        newWindow.document.close();
        newWindow.focus();
    });
});

/*****************************طباعة تفاصيل الرواتب******************* */
$(document).ready(function () {
    if (window.payrollDefaults) {
        let defaultMonth = window.payrollDefaults.month;
        let defaultYear = window.payrollDefaults.year;
        let defaultAdm = window.payrollDefaults.adm;
        let defaultAdmName = window.payrollDefaults.admName;

        if (defaultMonth && defaultYear && defaultAdm) {
            loadPayrollDetails(
                defaultMonth,
                defaultYear,
                defaultAdm,
                defaultAdmName
            );
        }
    }

    $(document).on("click", ".btn-adm", function () {
        let adm = $(this).data("adm");
        let admName = $(this).data("name");

        if (window.payrollDefaults) {
            let defaultMonth = window.payrollDefaults.month;
            let defaultYear = window.payrollDefaults.year;
            loadPayrollDetails(defaultMonth, defaultYear, adm, admName);
        }
    });
});

function loadPayrollDetails(month, year, adm, admName) {
    $("#report_department").text(admName);

    // عرض أيقونة التحميل
    Swal.fire({
        title: "جارٍ تحميل البيانات...",
        text: "يرجى الانتظار",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    $.ajax({
        url: `/paie/details/${month}/${year}/${adm}`,
        method: "GET",
        dataType: "json",
        success: function (response) {
            Swal.close(); // إخفاء التحميل عند النجاح

            if (response.salaryData && response.salaryData.length > 0) {
                $("#report_month").text(response.monthName);
                $("#report_year").text(response.year);

                let tableContent = response.salaryData
                    .map(
                        (data, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${data.Name}</td>
                        <td>${data.CATEG}<br>${data.ECH}</td>
                        <td>${data.SITFAM}<br>${data.ENF10}</td>
                        <td>${
                            data.BonusDetails.find((s) => s.IND == 1)
                                ?.MONTANT ?? "-"
                        }</td>
                        <td>${
                            data.BonusDetails.find((s) => s.IND == 101)
                                ?.MONTANT ?? "-"
                        }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 103)
                                    ?.MONTANT ?? "-"
                            }</td>
                        <td>${
                            data.BonusDetails.find((b) => b.IND == 208)
                                ?.MONTANT ?? "-"
                        }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 290)
                                    ?.MONTANT ?? "-"
                            }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 211)
                                    ?.MONTANT ?? "-"
                            }</td>
                        <td>${
                            data.BonusDetails.find((b) => b.IND == 225)
                                ?.MONTANT ?? "-"
                        }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 246)
                                    ?.MONTANT ?? "-"
                            }</td>
                        <td>${
                            data.BonusDetails.find((b) => b.IND == 260)
                                ?.MONTANT ?? "-"
                        }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 206)
                                    ?.MONTANT ?? "-"
                            }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 280)
                                    ?.MONTANT ?? "-"
                            }</td>
                        <td>${
                            data.BonusDetails.find((b) => b.IND == 271)
                                ?.MONTANT ?? "-"
                        }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 273)
                                    ?.MONTANT ?? "-"
                            }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 270)
                                    ?.MONTANT ?? "-"
                            }</td>
                        <td>${
                            data.BonusDetails.find((b) => b.IND == 242)
                                ?.MONTANT ?? "-"
                        }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 241)
                                    ?.MONTANT ?? "-"
                            }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 305)
                                    ?.MONTANT ?? "-"
                            }</td>
                        <td>${
                            data.BonusDetails.find((b) => b.IND == 401)
                                ?.MONTANT ?? "-"
                        }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 990)
                                    ?.MONTANT ?? "-"
                            }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 991)
                                    ?.MONTANT ?? "-"
                            }</td>
                        <td>${data.TOTGAIN}</td>
                        <td>${
                            data.BonusDetails.find((b) => b.IND == 610)
                                ?.MONTANT ?? "-"
                        }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 980)
                                    ?.MONTANT ?? "-"
                            }</td>
                        <td>${
                            data.BonusDetails.find((b) => b.IND == 397)
                                ?.MONTANT ?? "-"
                        }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 398)
                                    ?.MONTANT ?? "-"
                            }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 399)
                                    ?.MONTANT ?? "-"
                            }</td>
                        <td>${
                            data.BonusDetails.find((b) => b.IND == 301)
                                ?.MONTANT ?? "-"
                        }<br>
                            ${
                                data.BonusDetails.find((b) => b.IND == 302)
                                    ?.MONTANT ?? "-"
                            }</td>   
                        <td>${data.NBRTRAV}</td>
                        <td>${data.TotalSalary}</td>
                    </tr>
                `
                    )
                    .join("");

                $("#salary_table_body").html(tableContent);
                $("#salary_details").fadeIn();
                $("#printdetailsButton").fadeIn();
                $("#exportExcelBtn").fadeIn();
                $("#payrollModal").modal("hide");

                if ($("#salary_details").length) {
                    $("html, body").animate(
                        {
                            scrollTop: $("#salary_details").offset().top - 100,
                        },
                        500
                    );
                }
            } else {
                Swal.fire({
                    icon: "info",
                    title: "لا توجد بيانات",
                    text: "لا توجد بيانات متاحة للفترة المحددة",
                    confirmButtonText: "حسنا",
                });
            }
        },
        error: function () {
            Swal.close(); // إخفاء التحميل عند الخطأ
            Swal.fire({
                icon: "error",
                title: "خطأ",
                text: "لا يوجد كشف تفصيلي للراتب لهذا الشهر",
                confirmButtonText: "حسنا",
            });
            $("#payrollModal").modal("hide");
        },
    });
}

$("#printdetailsButton").on("click", function () {
    let printContent = document.getElementById(
        "salary_report_content"
    ).innerHTML;
    let newWindow = window.open("", "_blank");
    newWindow.document.open();
    newWindow.document.write(`
	<html dir="rtl">
	<head>
		<title>تقرير رواتب الموظفين</title>
        <style>
			@page {
				size: landscape;
				margin: 0.3cm;
			}
			body {
				font-family: 'Cairo', sans-serif;
				font-size: 11px;
				margin: 0;
				padding: 3px;
			}
			h3, h2 {
				text-align: center;
				margin: 2px 0;
				line-height: 1.1;
			}
			h4 {
				margin: 0;
				line-height: 1.1;
			}
			.report-header {
				margin-bottom: 3px;
			}
			.report-title {
				margin: 3px 0;
				position: relative;
			}
			.report-title:after {
				content: '';
				position: absolute;
				bottom: -2px;
				left: 50%;
				transform: translateX(-50%);
				width: 70px;
				height: 1px;
				background: #000;
			}
			table {
				width: 100%;
				border-collapse: collapse;
				margin: 3px auto;
				font-size: 8px;
			}
			th, td {
				border: 1px solid black;
				padding: 1px;
				text-align: center;
			}
			tr {
				line-height: 1;
			}
			th {
				background-color: #f2f2f2;
				font-weight: bold;
			}
			.alert {
				display: none;
			}
			@media print {
				@page {
					size: landscape;
					margin: 0.3cm;
				}
				body {
					margin: 0;
					padding: 3px;
				}
				table {
					width: 100%;
					page-break-inside: auto;
				}
				thead {
					display: table-header-group;
				}
				tr {
					page-break-inside: avoid;
					page-break-after: auto;
				}
			}
		</style>
	</head>
	<body onload="window.print();">
		${printContent}
	</body>
	</html>
	`);
    newWindow.document.close();
});
/**********************************dashboard*************************************/
$(document).ready(function () {
    alertify.set("notifier", "position", "bottom-left");
    alertify.set("notifier", "delay", 5);
});

/*********************************page messages receiverModal**************************************/
$(document).ready(function () {
    $("#main_group").on("change", function () {
        var mainGroupId = $(this).val();
        var mainGroupName = $("#main_group option:selected").text();
        if (mainGroupId) {
            $.ajax({
                url: "/sub-groups/" + mainGroupId,
                type: "GET",
                dataType: "json",
                success: function (data) {
                    $("#sub_group")
                        .empty()
                        .append('<option value="">يرجى اختيار..</option>');
                    $.each(data, function (key, value) {
                        var optionText = value.name.trim();
                        var isSameName = optionText === mainGroupName.trim();

                        if (isSameName) {
                            $("#sub_group").append(
                                '<option value="' +
                                    value.id +
                                    '" style="display:none;">' +
                                    value.name +
                                    "</option>"
                            );
                        } else {
                            $("#sub_group").append(
                                '<option value="' +
                                    value.id +
                                    '">' +
                                    value.name +
                                    "</option>"
                            );
                        }
                    });
                },
            });
        } else {
            $("#sub_group")
                .empty()
                .append('<option value="">يرجى اختيار..</option>');
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    function debounce(func, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    function filterItems(items, query) {
        let hasVisibleItems = false;

        items.forEach(function (item) {
            const label = item.querySelector("label").textContent.toLowerCase();
            const isVisible = label.includes(query);
            item.style.display = isVisible ? "" : "none";

            const collapse = item.querySelector(".collapse");
            if (collapse) {
                const subItems = collapse.querySelectorAll(".form-check");
                const hasVisibleSubItems = filterItems(subItems, query);
                if (hasVisibleSubItems) {
                    item.style.display = "";
                    $(collapse).collapse("show");
                } else {
                    $(collapse).collapse("hide");
                }
                hasVisibleItems = hasVisibleItems || hasVisibleSubItems;
            }

            hasVisibleItems = hasVisibleItems || isVisible;
        });

        return hasVisibleItems;
    }

    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener(
            "input",
            debounce(function () {
                const query = this.value.toLowerCase();
                document
                    .querySelectorAll(".tab-pane.show.active .list-group")
                    .forEach(function (listGroup) {
                        filterItems(
                            listGroup.querySelectorAll(".form-check"),
                            query
                        );
                    });
            }, 300)
        );
    }

    $(document).ready(function () {
        $(".toggle-btn").on("click", function () {
            const icon = $(this).find("i");
            const targetId = $(this).data("target");
            $(targetId).collapse("toggle");
            $(targetId).on("shown.bs.collapse", function () {
                icon.removeClass("fa-plus").addClass("fa-minus");
            });
            $(targetId).on("hidden.bs.collapse", function () {
                icon.removeClass("fa-minus").addClass("fa-plus");
            });
        });
    });
    $(".list-group-item").on("click", function (e) {
        e.preventDefault();
        const target = $(this).attr("href");
        $(".tab-pane").removeClass("show active");
        $(target).addClass("show active");
        $(".list-group-item").removeClass("active");
        $(this).addClass("active");
    });
    document
        .querySelectorAll('.form-check input[type="checkbox"]')
        .forEach(function (checkbox) {
            checkbox.addEventListener("change", function () {
                const collapse =
                    this.closest(".form-check").querySelector(".collapse");
                if (this.checked && collapse) {
                    collapse
                        .querySelectorAll('input[type="checkbox"]')
                        .forEach(function (subCheckbox) {
                            subCheckbox.checked = true;
                        });
                } else if (!this.checked && collapse) {
                    collapse
                        .querySelectorAll('input[type="checkbox"]')
                        .forEach(function (subCheckbox) {
                            subCheckbox.checked = false;
                        });
                }
                if (
                    this.closest(".form-check").classList.contains("main-group")
                ) {
                    this.checked = false;
                }
                updateSelectedCount();
            });
        });

    function updateSelectedCount() {
        const selectedCount = $('input[name="receiver_ids[]"]:checked').length;
        $("#selectedCount").text(selectedCount);
        $(".form-check .collapse").each(function () {
            const allChecked =
                $(this).find('input[type="checkbox"]').length ===
                $(this).find('input[type="checkbox"]:checked').length;

            if (allChecked) {
                const parentCheckbox = $(this)
                    .closest(".form-check")
                    .find('> input[type="checkbox"]');
                const groupName = parentCheckbox.next("label").text().trim();
                const groupId = parentCheckbox.val();
                $("#receiver_group_name").append(`
		<button type="button" class="btn strong btn-outline-warning btn-sm btn-fill recipient-btn main-group-btn" 
		data-id="${groupId}">        
		  ${groupName} <i class="fas fa-trash-alt remove-recipient"  style="padding-right: 0.5rem"></i>
		</button>
	   `);
                $(this)
                    .find('input[type="checkbox"]')
                    .each(function () {
                        const subId = $(this).val();
                        $(
                            `#receiver_group_name .recipient-btn[data-id="${subId}"]`
                        ).remove();
                    });
            }
            updateReceiverGroupDivHeight();
        });
    }

    function updateReceiverGroupDivHeight() {
        const receiverDiv = document.getElementById("receiver_group_name");
        receiverDiv.style.height = "auto";
        const height = receiverDiv.scrollHeight;
        if (height < 38) {
            receiverDiv.style.height = "38px";
        } else {
            receiverDiv.style.height = height + "px";
        }
    }
    $(document).on("click", "#saveRecipient", function () {
        alertify.set("notifier", "position", "bottom-left");
        let selectedNames = [];
        let selectedIds = [];
        updateReceiverGroupDivHeight();
        $('input[name="receiver_ids[]"]:checked').each(function () {
            selectedIds.push($(this).val());
            selectedNames.push($(this).next("label").text().trim());
        });
        if (selectedIds.length === 0) {
            alertify.notify("يرجـى تحـديد المستلمين", "error", 7);
            return;
        }
        $("#receiver_group_name").empty();
        selectedNames.forEach((name, index) => {
            const buttonClass =
                index === 0
                    ? "btn strong btn-outline-warning btn-sm btn-fill first-recipient"
                    : "btn strong btn-outline-warning btn-sm btn-fill";
            const button = ` 
			<button type="button" class="btn ${buttonClass} btn-sm recipient-btn" 
			data-id="${selectedIds[index]}">
			  ${name} <i class="fas fa-trash-alt remove-recipient" style="padding-right: 0.5rem"></i>
			</button>`;
            $("#receiver_group_name").append(button);
        });
        if ($("#receiverModal").hasClass("show")) {
            $("#receiverModal").modal("hide");
        }
        updateHiddenInput();
        updateReceiverGroupDivHeight();
        updateSelectedCount();
    });
    $("#receiver_group_name").on("click", ".remove-recipient", function () {
        $(this).closest(".recipient-btn").remove();
        updateReceiverGroupDivHeight();
        updateHiddenInput();
    });

    function updateHiddenInput() {
        const hiddenInput = $('input[name="receiver_group_id"]');
        const currentIds = [];
        $(".recipient-btn").each(function () {
            currentIds.push($(this).data("id"));
        });
        hiddenInput.val(currentIds.join(","));
    }
    $('input[name="receiver_ids[]"]').on("change", function () {
        updateSelectedCount();
    });
    updateSelectedCount();
    document.addEventListener("DOMContentLoaded", function () {
        const formElement = document.querySelector("form");
        if (formElement) {
            formElement.addEventListener("submit", function (event) {
                const submitButton = event.target.querySelector(
                    'button[type="submit"]'
                );
                if (submitButton) {
                    submitButton.disabled = true;
                }
            });
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const selectedFiles = [];
    const fileInput = document.getElementById("file-upload1");
    const attachmentList = document.getElementById("attachmentList");
    const fileCount = document.getElementById("fileCount");
    const dropArea = document.getElementById("dropArea");
    const uploadAllButton = document.getElementById("uploadAllButton");
    const MAX_TOTAL_SIZE_MB = 30;

    if (
        fileInput &&
        attachmentList &&
        fileCount &&
        uploadAllButton &&
        dropArea
    ) {
        // دالة لإضافة الملفات
        function addFiles(files) {
            let totalSizeMB = selectedFiles.reduce(
                (sum, f) => sum + f.size / 1024 / 1024,
                0
            );

            files.forEach((file) => {
                totalSizeMB += file.size / 1024 / 1024;
                if (totalSizeMB > MAX_TOTAL_SIZE_MB) {
                    alert("إجمالي حجم الملفات يتجاوز 30 م.ب");
                    totalSizeMB -= file.size / 1024 / 1024;
                    return;
                }
                if (
                    !selectedFiles.some(
                        (f) => f.name === file.name && f.size === file.size
                    )
                ) {
                    selectedFiles.push(file);
                }
            });

            syncFileInput();
            updateAttachmentList();
        }

        function syncFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach((f) => dataTransfer.items.add(f));
            fileInput.files = dataTransfer.files;
        }

        function updateAttachmentList() {
            attachmentList.innerHTML = "";
            fileCount.textContent = selectedFiles.length;

            selectedFiles.forEach((file, index) => {
                const listItem = document.createElement("tr");
                listItem.innerHTML = `
                    <td><strong>${file.name}</strong></td>
                    <td>${(file.size / 1024 / 1024).toFixed(2)} م.ب</td>
                    <td>
                        <div class="progress" style="margin-bottom: 0;">
                            <div id="progressBar${index}" class="progress-bar progress-bar-info progress-bar-striped" style="width: 0%;">0%</div>
                        </div>
                    </td>
                    <td class="text-center" id="status${index}" data-status="pending">
                        <h3 class="badge bg-success badge-sm w-100">في الانتظار</h3>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-success btn-sm upload-button" data-index="${index}"><i class="fas fa-upload"></i> تحميل</button>
                        <button class="btn btn-outline-danger btn-sm remove-file" type="button" data-index="${index}">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </td>`;
                attachmentList.appendChild(listItem);
            });
        }

        fileInput.addEventListener("change", function (event) {
            addFiles(Array.from(event.target.files));
        });

        dropArea.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropArea.classList.add("drag-over");
        });

        dropArea.addEventListener("dragleave", (e) => {
            e.preventDefault();
            dropArea.classList.remove("drag-over");
        });

        dropArea.addEventListener("drop", (e) => {
            e.preventDefault();
            dropArea.classList.remove("drag-over");
            addFiles(Array.from(e.dataTransfer.files));
        });

        attachmentList.addEventListener("click", function (event) {
            const target = event.target.closest("button");
            if (!target) return;

            const index = parseInt(target.getAttribute("data-index"), 10);

            if (target.classList.contains("remove-file")) {
                selectedFiles.splice(index, 1);
                syncFileInput();
                updateAttachmentList();
            }

            if (target.classList.contains("upload-button")) {
                uploadFile(index);
            }
        });

        function uploadFile(index) {
            const file = selectedFiles[index];
            const progressBar = document.getElementById(`progressBar${index}`);
            const status = document.getElementById(`status${index}`);

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "/upload", true);
            xhr.setRequestHeader(
                "X-CSRF-TOKEN",
                document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content")
            );

            xhr.upload.addEventListener("progress", function (event) {
                if (event.lengthComputable) {
                    const percentComplete = (event.loaded / event.total) * 100;
                    progressBar.style.width = percentComplete + "%";
                    progressBar.textContent = Math.round(percentComplete) + "%";
                }
            });

            xhr.onload = function () {
                if (xhr.status === 200) {
                    status.innerHTML =
                        '<h3 class="badge bg-primary badge-sm w-100">تم</h3>';
                    status.setAttribute("data-status", "success");
                    progressBar.classList.remove("progress-bar-info");
                    progressBar.classList.add("bg-success");
                } else {
                    status.innerHTML =
                        '<h3 class="badge bg-danger badge-sm w-100">فشل</h3>';
                    status.setAttribute("data-status", "failure");
                    progressBar.classList.remove("progress-bar-info");
                    progressBar.classList.add("bg-danger");
                }
            };

            xhr.onerror = function () {
                status.innerHTML =
                    '<h3 class="badge bg-danger badge-sm w-100">فشل</h3>';
                status.setAttribute("data-status", "failure");
                progressBar.classList.remove("progress-bar-info");
                progressBar.classList.add("bg-danger");
            };

            const formData = new FormData();
            formData.append("file", file);
            xhr.send(formData);
        }

        uploadAllButton.addEventListener("click", function () {
            selectedFiles.forEach((_, index) => {
                const status = document
                    .getElementById(`status${index}`)
                    .getAttribute("data-status");
                if (status === "pending") {
                    uploadFile(index);
                }
            });
        });
    }
});

/*******************************************************************************************************/
document.addEventListener("DOMContentLoaded", function () {
    const messagesList = document.querySelector("#messagesList");
    if (!messagesList) return;

    let lastMessageId = 0; // آخر ID معروف

    setInterval(() => {
        fetch(`/messages/latest?after_id=${lastMessageId}`, {
            credentials: "same-origin",
        })
            .then((res) => res.json())
            .then((data) => {
                data.messages.forEach((msg) => {
                    if (
                        !document.querySelector(`[data-message-id="${msg.id}"]`)
                    ) {
                        const newMessageHTML = `
                            <a data-message-id="${msg.id}" href="/messages/${
                            msg.slug
                        }" class="main-mail-item-link">
                                <div class="main-mail-item border-bottom-0">
                                    <div class="main-mail-checkbox">
                                        <label class="ckbox">
                                            <input type="checkbox" name="message_ids[]" value="${
                                                msg.id
                                            }">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="main-mail-star">
                                        <i class="typcn typcn-star"></i>
                                    </div>
                                    <div class="main-img-user">
                                        <img alt="user-img" class="avatar brround"
                                             src="/storage/${
                                                 msg.sender.avatar ??
                                                 "default-avatar.png"
                                             }">
                                    </div>
                                    <div class="main-mail-body">
                                        <div class="main-mail-from unread-group-name">
                                            ${
                                                msg.sender.sub_group?.name ??
                                                msg.sender.main_group?.name ??
                                                "المجموعة غير محددة"
                                            }
                                        </div>
                                        <div class="main-mail-subject">
                                            <strong class="unread-group-name">${
                                                msg.subject
                                            }</strong>
                                        </div>
                                    </div>
                                    <div class="main-mail-date">${
                                        msg.formatted_date
                                    }</div>
                                </div>
                            </a>
                        `;
                        messagesList.insertAdjacentHTML(
                            "afterbegin",
                            newMessageHTML
                        );

                        // تحديث آخر ID معروف
                        if (msg.id > lastMessageId) {
                            lastMessageId = msg.id;
                        }
                    }
                });
            })
            .catch((err) => console.error(err));
    }, 10000);
});

$(document).ready(function () {
    alertify.set("notifier", "position", "bottom-left");
    alertify.set("notifier", "delay", 3);
});

/*********************************** فحص الصورة قبل التحميل ********************************************************/
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#avatar-preview-img").attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

/*************************** فحص كلمة المرور **********************************************************************************/
document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("new_password");
    const confirmInput = document.getElementById("new_password_confirmation");

    if (passwordInput) {
        passwordInput.addEventListener("input", function () {
            checkPasswordStrength(passwordInput.value);
            validatePasswordMatch();
        });
    }

    if (confirmInput) {
        confirmInput.addEventListener("input", validatePasswordMatch);
    }
});

function checkPasswordStrength(password) {
    const strengthBar = document.getElementById("passwordStrengthBar");
    const feedback = document.getElementById("passwordStrengthText");

    if (!strengthBar || !password) {
        if (strengthBar) strengthBar.style.width = "0";
        if (feedback) feedback.textContent = "";
        return;
    }

    const hasLength = password.length >= 8;
    const hasLower = /[a-z]/.test(password);
    const hasUpper = /[A-Z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[^A-Za-z0-9]/.test(password);

    let strength = 0;
    if (password.length > 0) strength++;
    if (hasLength) strength++;
    if (hasLower) strength++;
    if (hasUpper) strength++;
    if (hasNumber) strength++;
    if (hasSpecial) strength++;

    const levels = ["ضعيفة جدًا", "ضعيفة", "متوسطة", "قوية", "قوية جدًا"];
    const colors = ["#dc3545", "#ffc107", "#fd7e14", "#20c997", "#28a745"];

    const index = Math.max(0, Math.min(strength - 1, levels.length - 1));
    const width = Math.min(100, (strength / 6) * 100);

    strengthBar.style.width = width + "%";
    strengthBar.style.backgroundColor = colors[index];

    if (feedback) {
        feedback.textContent = "قوة كلمة المرور: " + levels[index];
        feedback.style.color = colors[index];
    }
}

function validatePasswordMatch() {
    const passInput = document.getElementById("new_password");
    const confirmInput = document.getElementById("new_password_confirmation");
    const feedback = document.getElementById("confirmFeedback");

    // لو مفيش تأكيد → نخرج
    if (!passInput || !confirmInput || !feedback) return;

    if (
        confirmInput.value.length > 0 &&
        passInput.value !== confirmInput.value
    ) {
        feedback.classList.remove("d-none");
    } else {
        feedback.classList.add("d-none");
    }
}

function checkPasswordStrength(password) {
    const strengthBar = document.getElementById("passwordStrengthBar");
    const feedback = document.getElementById("passwordStrengthText");

    if (!strengthBar || !feedback) return;

    const hasLength = password.length >= 8;
    const hasLower = /[a-z]/.test(password);
    const hasUpper = /[A-Z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[^A-Za-z0-9]/.test(password);

    updateRequirement("length-check", hasLength);
    updateRequirement("uppercase-check", hasUpper);
    updateRequirement("number-check", hasNumber);
    updateRequirement("special-check", hasSpecial);

    let strength = 0;
    if (password.length > 0) strength++;
    if (hasLength) strength++;
    if (hasLower) strength++;
    if (hasUpper) strength++;
    if (hasNumber) strength++;
    if (hasSpecial) strength++;

    const levels = ["ضعيفة جدًا", "ضعيفة", "متوسطة", "قوية", "قوية جدًا"];
    const colors = ["#dc3545", "#ffc107", "#fd7e14", "#20c997", "#28a745"];

    // تحديد الفهرس بشكل آمن
    const index = Math.max(0, Math.min(strength - 1, levels.length - 1));

    const width = Math.min(100, (strength / 6) * 100);
    strengthBar.style.width = width + "%";
    strengthBar.style.backgroundColor = colors[index];
    strengthBar.setAttribute("aria-valuenow", width);

    feedback.textContent =
        password.length > 0 ? "قوة كلمة المرور: " + levels[index] : "";
    feedback.style.color = password.length > 0 ? colors[index] : "";
}

function validatePasswordMatch() {
    const pass = document.getElementById("new_password").value;
    const confirm = document.getElementById("new_password_confirmation").value;
    const feedback = document.getElementById("confirmFeedback");

    if (feedback) {
        if (confirm.length > 0 && pass !== confirm) {
            feedback.classList.remove("d-none");
        } else {
            feedback.classList.add("d-none");
        }
    }
}

function updateRequirement(id, isValid) {
    const element = document.getElementById(id);
    if (element) {
        element.classList.toggle("valid", isValid);
    }
}

/*****************صفحة التسجيل********************************************************************************/
$(document).ready(function () {
    $("#user_type").on("change", function () {
        const selectedType = $(this).val();
        const shouldShowInstitutionFields = [
            "admin",
            "office_head",
            "director",
            "manager",
            "inspector",
        ].includes(selectedType);

        if (shouldShowInstitutionFields) {
            $("#institution_fields").slideDown();
        } else {
            $("#institution_fields").slideUp();
            $("#main_group").val("");
            $("#sub_group")
                .empty()
                .append('<option value="">يرجى اختيار..</option>');
        }
    });

    $("#user_type").trigger("change");
});

const allowedGroupTypes = {
    admin: "admin",
    office_head: "admin",
    director: "education",
    manager: "education",
    inspector: "inspection",
};

$("#user_type").on("change", function () {
    const userType = $(this).val();

    if (userType in allowedGroupTypes) {
        const groupType = allowedGroupTypes[userType];

        $.ajax({
            url: "/filter-main-groups-by-type",
            method: "POST",
            data: {
                group_type: groupType,
                user_type: userType,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (data) {
                $("#main_group")
                    .empty()
                    .append('<option value="">يرجى اختيار..</option>');
                $.each(data, function (key, group) {
                    $("#main_group").append(
                        `<option value="${group.id}">${group.name}</option>`
                    );
                });
                $("#sub_group")
                    .empty()
                    .append('<option value="">يرجى اختيار..</option>');
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            },
        });
    }
});
// تحديث قائمة المجموعات الفرعية عند اختيار مجموعة رئيسية
$("#main_group").on("change", function () {
    const mainGroupId = $(this).val();
    if (mainGroupId) {
        $.ajax({
            url: "/sub-groups/" + mainGroupId,
            type: "GET",
            dataType: "json",
            success: function (data) {
                $("#sub_group")
                    .empty()
                    .append('<option value="">يرجى اختيار..</option>');
                $.each(data, function (key, value) {
                    $("#sub_group").append(
                        '<option value="' +
                            value.id +
                            '">' +
                            value.name +
                            "</option>"
                    );
                });
            },
        });
    } else {
        $("#sub_group")
            .empty()
            .append('<option value="">يرجى اختيار..</option>');
    }
});
/***************************************salary report***************************************************************/
$("#payrollreportForm").on("submit", function (e) {
    e.preventDefault();

    Swal.fire({
        title: "جاري تحميل البيانات",
        text: "يرجى الانتظار...",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    let matri = $("#search").val().trim();
    let startMonth = parseInt($("#start_month").val());
    let endMonth = parseInt($("#end_month").val());
    let year = parseInt($("#year").val());

    if (!matri || isNaN(startMonth) || isNaN(endMonth) || isNaN(year)) {
        Swal.close();
        Swal.fire({
            icon: "warning",
            title: "تحذير",
            text: "يرجى اختيار كل القيم المطلوبة (المعرف، السنة، الشهرين)",
        });
        return;
    }

    $.ajax({
        url: `/paie/details-report/${matri}/${year}/${startMonth}/${endMonth}`,
        method: "GET",
        dataType: "json",
        success: function (response) {
            Swal.close();

            if (response.salaries && response.salaries.length > 0) {
                const monthsMap = {
                    1: "جانفي",
                    2: "فيفري",
                    3: "مارس",
                    4: "أفريل",
                    5: "ماي",
                    6: "جوان",
                    7: "جويلية",
                    8: "أوت",
                    9: "سبتمبر",
                    10: "أكتوبر",
                    11: "نوفمبر",
                    12: "ديسمبر",
                };

                // دالة تنسيق المبالغ
                function formatMontant(details, ind) {
                    const found = details.find((b) => b.IND == ind);
                    return found?.MONTANT !== undefined
                        ? Number(found.MONTANT).toFixed(2)
                        : "-";
                }

                $("#employee_name").text(response.employee.full_name || "");
                $("#employee_rank").text(response.employee.rank || "");
                $("#from_month").text(monthsMap[startMonth] || startMonth);
                $("#to_month").text(monthsMap[endMonth] || endMonth);
                $("#report_year").text(year);

                let tableContent = response.salaries
                    .map(
                        (data) => `
                <tr>
                    <td>${data.month_arabic}</td>
                    <td>${data.CATEG}<br>${data.ECH}</td>
                    <td>${data.SITFAM}<br>${data.ENF10}</td>
                    <td>${formatMontant(data.BonusDetails, 1)}</td>
                    <td>${formatMontant(
                        data.BonusDetails,
                        101
                    )}<br>${formatMontant(data.BonusDetails, 103)}</td>
                    <td>${formatMontant(
                        data.BonusDetails,
                        208
                    )}<br>${formatMontant(
                            data.BonusDetails,
                            290
                        )}<br>${formatMontant(data.BonusDetails, 211)}</td>
                    <td>${formatMontant(
                        data.BonusDetails,
                        225
                    )}<br>${formatMontant(data.BonusDetails, 246)}</td>
                    <td>${formatMontant(
                        data.BonusDetails,
                        260
                    )}<br>${formatMontant(
                            data.BonusDetails,
                            206
                        )}<br>${formatMontant(data.BonusDetails, 280)}</td>
                    <td>${formatMontant(
                        data.BonusDetails,
                        271
                    )}<br>${formatMontant(
                            data.BonusDetails,
                            273
                        )}<br>${formatMontant(data.BonusDetails, 270)}</td>
                    <td>${formatMontant(
                        data.BonusDetails,
                        242
                    )}<br>${formatMontant(
                            data.BonusDetails,
                            241
                        )}<br>${formatMontant(data.BonusDetails, 305)}</td>
                    <td>${formatMontant(
                        data.BonusDetails,
                        401
                    )}<br>${formatMontant(
                            data.BonusDetails,
                            990
                        )}<br>${formatMontant(data.BonusDetails, 991)}</td>
                    <td>${Number(data.TOTGAIN).toFixed(2)}</td>
                    <td>${formatMontant(
                        data.BonusDetails,
                        610
                    )}<br>${formatMontant(data.BonusDetails, 980)}</td>
                    <td>${formatMontant(
                        data.BonusDetails,
                        397
                    )}<br>${formatMontant(
                            data.BonusDetails,
                            398
                        )}<br>${formatMontant(data.BonusDetails, 399)}</td>
                    <td>${formatMontant(
                        data.BonusDetails,
                        301
                    )}<br>${formatMontant(data.BonusDetails, 302)}</td>   
                    <td>${data.NBRTRAV}</td>
                    <td>${Number(data.NETPAI).toFixed(2)}</td>
                </tr>
            `
                    )
                    .join("");

                $("#salary_table_body").html(tableContent);
                $("#salary_report").fadeIn();
                $("#printreportButton").fadeIn();
                $("#payrollModal").modal("hide");

                $("html, body").animate(
                    {
                        scrollTop: $("#salary_report").offset().top - 100,
                    },
                    500
                );
            } else {
                $("#salary_report").hide();
                Swal.fire({
                    icon: "info",
                    title: "لا توجد بيانات",
                    text: "لا توجد بيانات متاحة للفترة المحددة",
                    confirmButtonText: "موافق",
                });
            }
        },
        error: function (xhr) {
            Swal.close();
            $("#salary_report").hide();
            Swal.fire({
                icon: "error",
                title: "خطأ",
                text:
                    xhr.responseJSON?.error ||
                    "لا توجد بيانات متاحة للفترة المحددة",
                confirmButtonText: "حسنًا",
            });
            $("#payrollModal").modal("hide");
        },
    });
});

$("#printreportButton").on("click", function () {
    let printContent = document.getElementById(
        "salary_report_content"
    ).innerHTML;
    let newWindow = window.open("", "_blank");
    newWindow.document.open();
    newWindow.document.write(`
	<html dir="rtl">
	<head>
		<title>تقرير رواتب الموظفين</title>
		<style>
			@page {
				size: landscape;
				margin: 0.3cm;
			}
			body {
				font-family: 'Cairo', sans-serif;
				font-size: 11px;
				margin: 0;
				padding: 3px;
			}
			h3, h2 {
				text-align: center;
				margin: 2px 0;
				line-height: 1.1;
			}
			h4 {
				margin: 0;
				line-height: 1.1;
			}
			.report-header {
				margin-bottom: 3px;
			}
			.report-title {
				margin: 3px 0;
				position: relative;
			}
			.report-title:after {
				content: '';
				position: absolute;
				bottom: -2px;
				left: 50%;
				transform: translateX(-50%);
				width: 70px;
				height: 1px;
				background: #000;
			}
			table {
				width: 100%;
				border-collapse: collapse;
				margin: 3px auto;
				font-size: 8px;
			}
			th, td {
				border: 1px solid black;
				padding: 1px;
				text-align: center;
			}
			tr {
				line-height: 1;
			}
			th {
				background-color: #f2f2f2;
				font-weight: bold;
			}
			.alert {
				display: none;
			}
			@media print {
				@page {
					size: landscape;
					margin: 0.3cm;
				}
				body {
					margin: 0;
					padding: 3px;
				}
				table {
					width: 100%;
					page-break-inside: auto;
				}
				thead {
					display: table-header-group;
				}
				tr {
					page-break-inside: avoid;
					page-break-after: auto;
				}
			}
		</style>
	</head>
	<body onload="window.print();">
		${printContent}
	</body>
	</html>
	`);
    newWindow.document.close();
});
/************************************************************results_salary*************************************************************/
$(document).ready(function () {
    // تهيئة DataTable
    $("#example1").DataTable({
        paging: true,
        pageLength: 10,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ مدخلات",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0",
            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
            paginate: {
                first: "الأول",
                last: "الأخير",
                next: "التالي",
                previous: "السابق",
            },
            zeroRecords: "لا توجد سجلات مطابقة",
            emptyTable: "لا توجد بيانات في الجدول",
            search: "بحث:",
        },
        responsive: true,
        autoWidth: false,
    });

    // دالة مشتركة لتحميل كشف الراتب
    function loadSalarySlip(matri, month, year, lang, callback) {
        Swal.fire({
            title: "جاري تحميل كشف الراتب...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });

        $.get(`/paie/salary-slip/${matri}/${month}/${year}?lang=${lang}`)
            .done(function (response) {
                Swal.close();
                $("#salary-slip").html(response);
                if (callback) callback(lang);
            })
            .fail(function (xhr) {
                Swal.close();
                let errorMessage =
                    xhr.responseJSON?.message || "حدث خطأ غير متوقع.";
                Swal.fire({
                    icon: "error",
                    title: "خطأ",
                    text: errorMessage,
                    confirmButtonText: "حسناً",
                });
            });
    }

    // عند الضغط على "عرض كشف الراتب" من جدول رئيسي
    $(document).on("click", ".payroll-link", function (e) {
        e.preventDefault();

        const matri = $(this).data("matri");
        const month = $(this).data("month");
        const year = $(this).data("year");
        const lang = $(this).data("lang") || "ar";

        loadSalarySlip(matri, month, year, lang, function () {
            $("#payroll-details").fadeIn();
            $("#employee-list").hide();

            $("#toggleLangBtn")
                .data("matri", matri)
                .data("month", month)
                .data("year", year)
                .data("lang", "fr")
                .html('<i class="fas fa-file-alt mr-2"></i> فرنسي');

            $("html, body").animate(
                {
                    scrollTop: $("#payroll-details").offset().top - 50,
                },
                500
            );
        });
    });

    // زر التبديل بين عربي وفرنسي
    $(document).on("click", "#toggleLangBtn", function (e) {
        e.preventDefault();

        let $btn = $(this);
        let currentLang = $btn.data("lang");
        let matri = $btn.data("matri");
        let month = $btn.data("month");
        let year = $btn.data("year");

        loadSalarySlip(matri, month, year, currentLang, function (lang) {
            if (lang === "fr") {
                $("#salary-slip").attr("dir", "ltr");
                $btn.data("lang", "ar").html(
                    '<i class="fas fa-file-alt mr-2"></i> عربي'
                );
            } else {
                $("#salary-slip").attr("dir", "rtl");
                $btn.data("lang", "fr").html(
                    '<i class="fas fa-file-alt mr-2"></i> فرنسي'
                );
            }
        });
    });

    // البحث عن الموظفين من قاعدة البيانات
    $("#employeeSearch").on("keyup", function () {
        let query = $(this).val();

        if (query.length < 2) {
            $("#allEmployeesTable tbody").html(
                "<tr><td colspan='5' class='text-center'>🔍 اكتب على الأقل حرفين للبحث...</td></tr>"
            );
            return;
        }
        $.ajax({
            url: "/employees/search",
            method: "GET",
            data: {
                q: query,
            },
            success: function (data) {
                let rows = "";
                if (data.length > 0) {
                    $.each(data, function (index, emp) {
                        rows += `
                          <tr>
                            <td>${emp.NOMA} ${emp.PRENOMA}</td>
                            <td>
                              <button class="btn btn-sm btn-primary select-employee" data-matri="${emp.MATRI}">
                                اختيار
                              </button>
                            </td>
                          </tr>`;
                    });
                } else {
                    rows =
                        "<tr><td colspan='5' class='text-center text-danger'>❌ لا توجد نتائج مطابقة</td></tr>";
                }
                $("#allEmployeesTable tbody").html(rows);
            },
            error: function () {
                $("#allEmployeesTable tbody").html(
                    "<tr><td colspan='5' class='text-center text-danger'>⚠️ حدث خطأ في جلب البيانات</td></tr>"
                );
            },
        });
    });

    // اختيار موظف من نتائج البحث
    $(document).on("click", ".select-employee", function () {
        let matri = $(this).data("matri");

        // الشهر والسنة من Blade
        let currentMonth = window.payrollConfig.month;
        let currentYear = window.payrollConfig.year;

        loadSalarySlip(matri, currentMonth, currentYear, "ar", function () {
            $("#payroll-details").fadeIn();

            $("#toggleLangBtn")
                .data("matri", matri)
                .data("month", currentMonth)
                .data("year", currentYear)
                .data("lang", "fr")
                .html('<i class="fas fa-file-alt mr-2"></i> فرنسي');

            $("#employeeListModal").modal("hide");

            $("html, body").animate(
                {
                    scrollTop: $("#payroll-details").offset().top - 50,
                },
                500
            );
            $("#employeeListModal").modal("hide");
        });
    });
});
// زر إظهار/إخفاء منحة المردودية
$(document).on("click", "#toggleMrdiyyaBtn", function () {
    let $btn = $(this);
    let $mrdiyya = $("#salary-slip").find(".mrdiyya-row");

    if ($mrdiyya.length) {
        if ($mrdiyya.is(":visible")) {
            $mrdiyya.hide();
            $(".net-salary-only").show(); // عرض الصف الأساسي
            $btn.html('<i class="fas fa-gift mr-2"></i> إظهار منحة المردودية');
        } else {
            $mrdiyya.show();
            $(".net-salary-only").hide(); // إخفاء الصف الأساسي
            $btn.html(
                '<i class="fas fa-eye-slash mr-2"></i> إخفاء منحة المردودية'
            );
        }
    } else {
        Swal.fire({
            icon: "info",
            title: "تنبيه",
            text: "⚠️ لا توجد منحة مردودية لهذا الشهر.",
            confirmButtonText: "حسنا",
        });
    }
});
/*************************active user************************* */
$(document).ready(function () {
    alertify.set("notifier", "position", "bottom-left");
    alertify.set("notifier", "container", "alert-container");
    alertify.set("notifier", "delay", 5);

    $(document)
        .off("change", ".toggle-active")
        .on("change", ".toggle-active", function () {
            const userId = $(this).data("id");
            const isActive = $(this).is(":checked");
            const statusIndicator = $(this)
                .closest("td")
                .find(".status-indicator");

            if (isActive) {
                statusIndicator
                    .removeClass("status-inactive")
                    .addClass("status-active");
            } else {
                statusIndicator
                    .removeClass("status-active")
                    .addClass("status-inactive");
            }

            $.ajax({
                url: isActive
                    ? `${window.appConfig.activateUrl}/${userId}`
                    : `${window.appConfig.deactivateUrl}/${userId}`,
                type: "POST",
                data: {
                    _token: window.appConfig.csrf,
                },
                success: function (response) {
                    alertify.success(response.message);
                },
                error: function () {
                    // رجّع الحالة الأصلية لو صار خطأ
                    if (isActive) {
                        statusIndicator
                            .removeClass("status-active")
                            .addClass("status-inactive");
                    } else {
                        statusIndicator
                            .removeClass("status-inactive")
                            .addClass("status-active");
                    }
                    $(`.toggle-active[data-id="${userId}"]`).prop(
                        "checked",
                        !isActive
                    );
                    alertify.error("حدث خطأ أثناء تفعيل/تعطيل المستخدم");
                },
            });
        });

    // DataTable
    if ($.fn.DataTable.isDataTable("#activeuser")) {
        $("#activeuser").DataTable().clear().destroy();
    }
    $("#activeuser").DataTable({
        paging: true,
        pageLength: 10,
        ordering: true,
        order: [[0, "asc"]],
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ مدخلات",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
            paginate: {
                first: "الأول",
                last: "الأخير",
                next: "التالي",
                previous: "السابق",
            },
            zeroRecords: "لا توجد سجلات مطابقة",
            emptyTable: "لا توجد بيانات في الجدول",
            search: "بحث:",
        },
        responsive: true,
        autoWidth: false,
    });

    $(".dataTables_filter input").attr("placeholder", "ابحث هنا...");
});
/**********************************************************index user*************************************************** */
$(document).ready(function () {
    $(".transfer-button").on("click", function () {
        let employeeId = $(this).data("employee-id");
        let employeeName = $(this).data("employee-name");
        let employeeGrade = $(this).data("employee-codfonc");
        let employeeAffect = $(this).data("employee-affect");

        $("#employeeId").val("");
        $("#CODFONC").val("").change();
        $("#AFFECT").val("").change();

        $("#employeeId").val(employeeId);
        $("#CODFONC").val(employeeGrade).change();
        $("#AFFECT").val(employeeAffect).change();

        $("#transferModalLabel").text("تحويل الموظف: " + employeeName);
        $("#transferModal").modal("show");
    });

    $("#transferModal").on("hidden.bs.modal", function () {
        $("#employeeId").val("");
        $("#CODFONC").val("").change();
        $("#AFFECT").val("").change();
    });

    $("#confirmTransfer").on("click", function () {
        $("#transferForm").submit();
    });

    if ($.fn.DataTable.isDataTable("#usersTable")) {
        $("#usersTable").DataTable().destroy();
    }

    var table = $("#usersTable").DataTable({
        paging: true,
        pageLength: 10,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ مدخلات",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
            paginate: {
                first: "الأول",
                last: "الأخير",
                next: "التالي",
                previous: "السابق",
            },
            zeroRecords: "لا توجد سجلات مطابقة",
            emptyTable: "لا توجد بيانات في الجدول",
            search: "بحث:",
        },
        responsive: true,
        autoWidth: false,
    });

    $(".adm-filter-btn").on("click", function () {
        var adm = $(this).data("adm");
        var url = new URL(window.location.href);
        if (adm) {
            url.searchParams.set("adm", adm);
        } else {
            url.searchParams.delete("adm");
        }
        window.location.href = url.toString();
    });
});
/********************************************************edit user********************************************************* */
$("#main_group").change(function () {
    var mainGroupId = $(this).val();
    if (mainGroupId) {
        $.ajax({
            url: "/get-sub-groups/" + mainGroupId,
            type: "GET",
            success: function (data) {
                var subGroupSelect = $("#sub_group");
                subGroupSelect.empty();
                if (data.length) {
                    $.each(data, function (index, subGroup) {
                        subGroupSelect.append(
                            $("<option>", {
                                value: subGroup.id,
                                text: subGroup.name,
                            })
                        );
                    });
                } else {
                    subGroupSelect.append(
                        $("<option>", {
                            value: "",
                            text: "لا توجد مجموعات فرعية متاحة",
                        })
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error("حدث خطأ:", error);
            },
        });
    } else {
        $("#sub_group")
            .empty()
            .append(
                $("<option>", {
                    value: "",
                    text: "اختر مجموعة رئيسية أولاً",
                })
            );
    }
});

/************************************** transfer user modal *************************************/
$(document).ready(function () {
    $(".transfer-button").on("click", function () {
        let employeeId = $(this).data("employee-id");
        let employeeName = $(this).data("employee-name");
        let employeeGrade = $(this).data("employee-codfonc");
        let employeeAffect = $(this).data("employee-affect");

        $("#employeeId").val("");
        $("#CODFONC").val("").change();
        $("#AFFECT").val("").change();

        $("#employeeId").val(employeeId);
        $("#CODFONC").val(employeeGrade).change();
        $("#AFFECT").val(employeeAffect).change();

        $("#transferModalLabel").text("تحويل الموظف: " + employeeName);
        $("#transferModal").modal("show");
    });

    $("#transferModal").on("hidden.bs.modal", function () {
        $("#employeeId").val("");
        $("#CODFONC").val("").change();
        $("#AFFECT").val("").change();
    });
    $("#confirmTransfer").on("click", function () {
        $("#transferForm").submit();
    });

    $("#transferTable").DataTable({
        paging: true,
        pageLength: 10,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ مدخلات",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
            paginate: {
                first: "الأول",
                last: "الأخير",
                next: "التالي",
                previous: "السابق",
            },
            zeroRecords: "لا توجد سجلات مطابقة",
            emptyTable: "لا توجد بيانات في الجدول",
            search: "بحث:",
        },
        responsive: true,
        autoWidth: false,
        dom: "Blfrtip",
        buttons: [],
    });

    $(".dataTables_filter input").attr("placeholder", "ابحث هنا...");

    if (window.innerWidth < 768) {
        $("#transferTable").addClass("responsive-table");
    }

    $("#search").focus();
});
/**********************************************index employee list************************************************ */
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable("#emp")) {
        $("#emp").DataTable().destroy();
    }
    $("#emp").DataTable({
        paging: true,
        pageLength: 10,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ في كل صفحة",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
            paginate: {
                first: "الأول",
                last: "الأخير",
                next: "التالي",
                previous: "السابق",
            },
            zeroRecords: "لا توجد سجلات مطابقة",
            emptyTable: "لا توجد بيانات في الجدول",
            search: "بحث:",
        },
        responsive: true,
        autoWidth: false,
        dom: "Blfrtip",
        buttons: [
            {
                extend: "print",
                text: "طباعة",
                className: "btn btn-primary",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                },
            },
            {
                extend: "excel",
                text: "تصدير إلى Excel",
                className: "btn btn-success",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                },
            },
        ],
    });

    // Enhance search box
    $(".dataTables_filter input").attr("placeholder", "ابحث هنا...");

    // Add responsive data attributes for mobile view
    if (window.innerWidth < 768) {
        $("#emp").addClass("responsive-table");
    }
});

$("#employee-form").submit(function (e) {
    Swal.fire({
        title: "جاري التحميل",
        text: "يرجى الانتظار أثناء معالجة البيانات...",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
});
/******************************************add employee***************************************************************/
$(document).ready(function () {
    /**
     *  🔹 تهيئة الـ Wizard (إضافة أو تعديل)
     */
    function initWizard(isEdit = false) {
        $("#wizard1").steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "fade",
            autoFocus: true,
            labels: {
                next: "التالي",
                previous: "السابق",
                finish: isEdit ? "تحديث" : "إضافة",
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                if (newIndex < currentIndex) return true;

                // في حالة صفحة التعديل نستعمل jQuery Validation
                if (isEdit) {
                    let form = $(this).closest("form");
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                }

                // في حالة الإضافة (تحقق يدوي)
                const currentStep = $(`#wizard1-p-${currentIndex}`);
                let valid = true;

                currentStep.find("input, select, textarea").each(function () {
                    const fieldType = $(this).attr("type");

                    if (fieldType === "radio") {
                        const radioGroupName = $(this).attr("name");
                        if (
                            $(`input[name="${radioGroupName}"]:checked`)
                                .length === 0
                        ) {
                            valid = false;
                            if (
                                !$(this)
                                    .closest(".form-group")
                                    .find(".invalid-feedback").length
                            ) {
                                $(this)
                                    .closest(".form-group")
                                    .append(
                                        '<div class="invalid-feedback">يرجى اختيار أحد الخيارات.</div>'
                                    );
                            }
                        } else {
                            $(this)
                                .closest(".form-group")
                                .find(".invalid-feedback")
                                .remove();
                        }
                    } else {
                        if ($(this).val().trim() === "") {
                            valid = false;
                            $(this).addClass("is-invalid");
                            if (!$(this).next(".invalid-feedback").length) {
                                $(this).after(
                                    '<div class="invalid-feedback">هذا الحقل إلزامي</div>'
                                );
                            }
                        } else {
                            $(this).removeClass("is-invalid");
                            $(this).next(".invalid-feedback").remove();
                        }
                    }
                });

                return valid;
            },
            onFinished: function () {
                const form = $(this).closest("form");

                if (isEdit) {
                    alertify.success("تم تحديث معلومات الموظف بنجاح!");
                    form.submit();
                } else {
                    Swal.fire({
                        title: "تم إضافة الموظف بنجاح!",
                        icon: "success",
                        confirmButtonText: "حسنًا",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            },
            onStepChanged: function (event, currentIndex) {
                history.replaceState(null, null, " ");
                $(`#wizard1-p-${currentIndex} select`).select2({
                    width: "100%",
                    placeholder: "--الرجاء الاختيار--",
                    dir: "rtl",
                    language: "ar",
                });
            },
        });
    }

    /**
     *  🔹 Ajax لجلب المجموعات الفرعية
     */
    function initGroupChange() {
        $("#main_group").on("change", function () {
            let mainGroupId = $(this).val();
            let $subGroup = $("#sub_group");

            $subGroup
                .empty()
                .append('<option value="">-- الرجاء الاختيار --</option>');

            if (mainGroupId) {
                $.ajax({
                    url: window.appRoutes.getSubGroups,
                    type: "GET",
                    data: { main_group_id: mainGroupId },
                    success: function (data) {
                        if (data.length === 0) {
                            alertify.warning("لا توجد مجموعات فرعية متاحة");
                            return;
                        }
                        $.each(data, function (_, group) {
                            $subGroup.append(
                                `<option value="${group.id}">${group.name}</option>`
                            );
                        });

                        if ($subGroup.hasClass("select2-hidden-accessible")) {
                            $subGroup.trigger("change.select2");
                        } else {
                            $subGroup.select2({
                                width: "100%",
                                placeholder: "--الرجاء الاختيار--",
                                dir: "rtl",
                                language: "ar",
                            });
                        }
                    },
                    error: function (xhr) {
                        alertify.error("فشل جلب المجموعات الفرعية");
                        console.error(
                            "Error fetching sub-groups:",
                            xhr.responseText
                        );
                    },
                });
            }
        });
    }
    if ($("#wizard1").length) {
        let isEdit = $("#wizard1").data("mode") === "edit";
        initWizard(isEdit);
    }
    initGroupChange();
});

/**********************************************************statistics employee *************************************************/
$(document).ready(function () {
    $("#gradesTable").DataTable({
        paging: true,
        pageLength: 10,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ في كل صفحة",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
            paginate: {
                first: "الأول",
                last: "الأخير",
                next: "التالي",
                previous: "السابق",
            },
            zeroRecords: "لا توجد سجلات مطابقة",
            emptyTable: "لا توجد بيانات في الجدول",
            search: "بحث:",
        },
        responsive: true,
        autoWidth: false,
        dom: "Bfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                text: '<i class="fas fa-file-excel ml-2"></i>تصدير إلى Excel',
                className: "btn btn-success",
            },
            {
                extend: "pdfHtml5",
                text: '<i class="fas fa-file-pdf ml-2"></i>تصدير إلى PDF',
                className: "btn btn-danger",
            },
            {
                extend: "print",
                text: '<i class="fas fa-print ml-2"></i>طباعة',
                className: "btn btn-primary",
            },
        ],
    });

    $("#groupsTable").DataTable({
        paging: true,
        pageLength: 10,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ في كل صفحة",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
            paginate: {
                first: "الأول",
                last: "الأخير",
                next: "التالي",
                previous: "السابق",
            },
            zeroRecords: "لا توجد سجلات مطابقة",
            emptyTable: "لا توجد بيانات في الجدول",
            search: "بحث:",
        },
        responsive: true,
        autoWidth: false,
        dom: "Bfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                text: '<i class="fas fa-file-excel ml-2"></i>تصدير إلى Excel',
                className: "btn btn-success",
            },
            {
                extend: "pdfHtml5",
                text: '<i class="fas fa-file-pdf ml-2"></i>تصدير إلى PDF',
                className: "btn btn-danger",
            },
            {
                extend: "print",
                text: '<i class="fas fa-print ml-2"></i>طباعة',
                className: "btn btn-primary",
            },
        ],
    });

    if ($("#gradesChart").length > 0) {
        if (window.gradesData && window.gradesData.length > 0) {
            Morris.Donut({
                element: "gradesChart",
                data: window.gradesData,
                colors: [
                    "#0162e8",
                    "#00cccc",
                    "#5b67c7",
                    "#f16d75",
                    "#ffbd5a",
                    "#28a745",
                    "#17a2b8",
                    "#6f42c1",
                ],
                resize: true,
                formatter: function (value) {
                    return value + " موظف";
                },
            });
        } else {
            $("#gradesChart").html(
                '<div class="text-center p-5">لا توجد بيانات كافية لعرض الرسم البياني</div>'
            );
        }
    }

    if ($("#groupsChart").length > 0) {
        if (window.groupsData && window.groupsData.length > 0) {
            Morris.Bar({
                element: "groupsChart",
                data: window.groupsData,
                xkey: "y",
                ykeys: ["a"],
                labels: ["عدد الموظفين"],
                barColors: ["#0162e8"],
                gridTextSize: 11,
                hideHover: "auto",
                resize: true,
                gridLineColor: "#e5e9f2",
                gridTextFamily: "Cairo",
            });
        } else {
            $("#groupsChart").html(
                '<div class="text-center p-5">لا توجد بيانات كافية لعرض الرسم البياني</div>'
            );
        }
    }
});

/*****************************************index groups**************************************************************/
$("#formImport").submit(function (e) {
    Swal.fire({
        title: "جاري التحميل",
        text: "يرجى الانتظار أثناء معالجة البيانات...",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
});
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable("#groupsTable")) {
        $("#groupsTable").DataTable().destroy();
    }
    $("#groupsTable").DataTable({
        paging: true,
        pageLength: 10,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ مدخلات",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
            paginate: {
                first: "الأول",
                last: "الأخير",
                next: "التالي",
                previous: "السابق",
            },
            zeroRecords: "لا توجد سجلات مطابقة",
            emptyTable: "لا توجد بيانات في الجدول",
            search: "بحث:",
        },
        responsive: true,
        autoWidth: false,
        dom: "Bfrtip",
        buttons: [
            {
                extend: "excelHtml5",
                text: '<i class="fas fa-file-excel ml-2"></i>تصدير إلى Excel',
                className: "btn btn-success",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                },
            },
            {
                extend: "pdfHtml5",
                text: '<i class="fas fa-file-pdf ml-2"></i>تصدير إلى PDF',
                className: "btn btn-danger",
                exportOptions: {
                    columns: [0, 1, 2, 3, 4],
                },
            },
        ],
    });
    $(".dataTables_filter input").addClass("form-control");
    $(".dataTables_filter input").attr("placeholder", "بحث سريع...");
    $("#groupsTable tbody tr").hover(
        function () {
            $(this).addClass("bg-light");
        },
        function () {
            $(this).removeClass("bg-light");
        }
    );
});
/***********************************************add groups*********************************************************/
$(document).ready(function () {
    $(".select2").select2({
        placeholder: "اختر مؤسسة...",
        allowClear: true,
        width: "100%",
        dir: "rtl",
        language: "ar",
    });

    $(".form-control")
        .focus(function () {
            $(this).closest(".form-group").addClass("focused");
        })
        .blur(function () {
            $(this).closest(".form-group").removeClass("focused");
        });
});
/***********************************************edit groups*********************************************************/
$(document).ready(function () {
    $(".select2").select2({
        placeholder: "اختر مؤسسة...",
        allowClear: true,
        width: "100%",
        dir: "rtl",
        language: "ar",
    });

    $(".form-control")
        .focus(function () {
            $(this).closest(".form-group").addClass("focused");
        })
        .blur(function () {
            $(this).closest(".form-group").removeClass("focused");
        });

    $("#parent_id").on("change", function () {
        var selectedId = $(this).val();
        var currentId = "{{ $group->id }}";

        if (selectedId === currentId) {
            Swal.fire({
                icon: "error",
                title: "خطأ",
                text: "لا يمكن اختيار المؤسسة نفسها كمؤسسة أم",
                confirmButtonText: "حسناً",
            });
            $(this).val("").trigger("change");
        }
    });
});
/*********************************************** prime rendement create ****************************************************/
$(document).ready(function () {
    const $table = $("#primeTable");
    if (!$table.length) return;

    alertify.set("notifier", "position", "bottom-left");

    // ======== تهيئة DataTable ========
    const table = $table.DataTable({
        lengthChange: true,
        pageLength: 10,
        ordering: false,
        responsive: true,
        autoWidth: false,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ في كل صفحة",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0",
            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
            paginate: {
                first: "الأول",
                last: "الأخير",
                next: "التالي",
                previous: "السابق",
            },
            zeroRecords: "لا توجد سجلات مطابقة",
            emptyTable: "لا توجد بيانات في الجدول",
            search: "بحث:",
        },
    });

    // ======== تحديد الصف عند النقر ========
    $table.on("click", "tbody tr", function (e) {
        if (!$(e.target).is("input, button, i")) {
            $(this).toggleClass("selected");
        }
    });

    // ======== التحقق من القيم ========
    $table.on("change", ".mark, .absence_days", function () {
        const $input = $(this);
        const max = parseInt($input.attr("max")) || 0;
        let val = parseInt($input.val()) || 0;

        if (val > max)
            $input.val(max),
                alertify.warning(`القيمة القصوى المسموح بها هي ${max}`);
        else if (val < 0)
            $input.val(0), alertify.warning("القيمة لا يمكن أن تكون أقل من 0");

        $input.closest("tr").addClass("selected");
    });

    // ======== زر إعطاء العلامة الكاملة ========
    $("#set-full-marks").on("click", function () {
        table.rows().every(function () {
            const $row = $(this.node());
            const $mark = $row.find("input.mark");
            const max = parseInt($mark.attr("max")) || 0;
            if (max > 0) $mark.val(max), $row.addClass("selected");
        });
        alertify.success("تم إعطاء العلامة الكاملة لجميع الموظفين");
    });

    // ======== زر الحفظ ========
    $("#save-all").on("click", function () {
        const data = [];
        table.rows(".selected").every(function () {
            const $row = $(this.node());
            const matri = $row.data("matri") || $row.find("td:first").text();
            const mark = $row.find("input.mark").val();
            const absence = $row.find("input.absence_days").val() || 0;
            const notes = $row.find("input.notes").val() || null;
            if (matri && mark !== "")
                data.push({ MATRI: matri, mark, absence_days: absence, notes });
        });

        if (!data.length)
            return alertify.warning("لا توجد بيانات جديدة للحفظ.");

        const $btn = $(this);
        $btn.prop("disabled", true).text("جارٍ الحفظ...");
        $.ajax({
            url: window.primeConfig.storeUrl,
            type: "POST",
            contentType: "application/json",
            headers: { "X-CSRF-TOKEN": window.primeConfig.csrf },
            data: JSON.stringify({
                year: window.primeConfig.year,
                quarter: window.primeConfig.quarter,
                employees: data,
            }),
            success: function (res) {
                if (res.success)
                    alertify.success("✅ تم حفظ البيانات بنجاح!"),
                        table
                            .rows(".selected")
                            .nodes()
                            .to$()
                            .removeClass("selected");
            },
            error: function () {
                alertify.error("⚠️ حدث خطأ أثناء الحفظ");
            },
            complete: function () {
                $btn.prop("disabled", false).html(
                    '<i class="fa-solid fa-floppy-disk"></i> حفظ'
                );
            },
        });
    });

    // ======== زر إلغاء الحفظ ========
    $("#reset-all").on("click", function () {
        const currentAdm = $(".adm-filter-btn.btn-primary").data("adm");
        if (!currentAdm) return Swal.fire("⚠️ يرجى اختيار إدارة أولاً");

        Swal.fire({
            title: "تأكيد",
            text: "هل أنت متأكد من إلغاء جميع بيانات موظفي هذه الإدارة فقط؟",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "نعم",
            cancelButtonText: "لا",
        }).then((result) => {
            if (result.isConfirmed) {
                // مسح الحقول مباشرة بدون عدّ
                table.rows().every(function () {
                    const $row = $(this.node());
                    if ($row.data("adm") == currentAdm) {
                        $row.find("input.mark, input.notes")
                            .val("")
                            .trigger("input")
                            .trigger("change");
                    }
                });

                // إرسال الطلب للسيرفر
                $.ajax({
                    url: window.primeConfig.resetUrl,
                    type: "POST",
                    headers: { "X-CSRF-TOKEN": window.primeConfig.csrf },
                    contentType: "application/json",
                    data: JSON.stringify({
                        year: window.primeConfig.year,
                        quarter: window.primeConfig.quarter,
                        ADM: currentAdm,
                    }),
                    success: function (res) {
                        alertify.success("✅ تم مسح بيانات الموظفين بنجاح!");
                    },
                    error: function () {
                        alertify.error("⚠️ حدث خطأ أثناء عملية الإلغاء");
                    },
                });
            }
        });
    });

    // ======== فلترة حسب الإدارة ========
    $(".adm-filter-btn").on("click", function () {
        const admValue = $(this).data("adm");
        table
            .column(3)
            .search(admValue || "")
            .draw();
        $(".adm-filter-btn")
            .removeClass("btn-primary")
            .addClass("btn-outline-primary");
        $(this).removeClass("btn-outline-primary").addClass("btn-primary");
    });

    $(".dataTables_filter input").attr("placeholder", "ابحث هنا...");

    // ======== الطباعة ========
    $("#print-rendements").on("click", function () {
        const currentAdmBtn = $(".adm-filter-btn.btn-primary");
        const currentAdm = currentAdmBtn.length
            ? currentAdmBtn.text().trim()
            : "غير محددة";
        window.rendementPrint.adm = currentAdm;

        const visibleRows = table
            .rows({ search: "applied" })
            .nodes()
            .to$()
            .clone();
        visibleRows.find("input").each(function () {
            const value = $(this).val();
            $(this).replaceWith(`<span>${value}</span>`);
        });

        visibleRows.find("span.input-group-text").remove();

        const thead = $("#primeTable thead").clone();
        const clonedTable = $("<table class='print-table'/>")
            .append(thead)
            .append($("<tbody/>").append(visibleRows));

        const printWindow = window.open("", "_blank", "width=1000,height=700");
        if (!printWindow)
            return alert("⚠️ المتصفح منع نافذة الطباعة (Pop-up blocked)");

        const html = `
    <html dir="rtl" lang="ar">
    <head>
        <meta charset="UTF-8">
        <title>قائمة المردودية</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 10px; direction: rtl; }
            h2 { text-align:center; margin:0; padding:0; }
            h3, h4, h5 { margin:0; padding:0; }
            .print-header { margin-bottom:10px; padding-bottom:5px; }
            .print-info { display:flex; justify-content:space-between; margin-top:10px; margin-bottom:10px; }
            .print-info h5:first-child { font-weight:bold; text-align:right; } /* اسم الإدارة بالسمين واليمين */
            .print-info h5:last-child { text-align:left; } /* تاريخ الطباعة باليسار */
            table { width:100%; border-collapse:collapse; margin-top:10px; }
            th, td { border:1px solid #000; padding:5px; text-align:center; }
            th { background-color:#f2f2f2; }
            .footer { margin-top:30px; display:flex; justify-content:space-between; }
            .signature { border-top:1px solid #000; padding-top:10px; width:200px; text-align:center; }
        </style>
    </head>
    <body>
        <div class="print-header">
            <h2>الجمهورية الجزائرية الديمقراطية الشعبية</h2>
            <h2>وزارة التربية الوطنية</h2>
            <h3>مديرية التربية لولاية المغير</h3>
            <h3>${window.rendementPrint.subGroup}</h3>
            <h2>نقاط المردودية الثلاثي: ${window.rendementPrint.period} ${
            window.rendementPrint.year
        }</h2>
            <div class="print-info">
                <h5>الإدارة: ${window.rendementPrint.adm}</h5>
                <h5>تاريخ الطباعة: ${new Date().toLocaleDateString(
                    "ar-DZ"
                )}</h5>
            </div>
        </div>
        <div class="table-container">${clonedTable[0].outerHTML}</div>
        <div class="footer">
            <div class="signature"><p></p></div>
            <div class="signature"><p>مدير المؤسسة</p></div>
        </div>
    </body>
    </html>`;

        printWindow.document.open();
        printWindow.document.write(html);
        printWindow.document.close();
        printWindow.onload = () => {
            printWindow.focus();
            printWindow.print();
        };
    });
});

/***********************************************************payroll index*************************************************************** */
function initDataTable(selector) {
    if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().destroy();
    }
    $(selector).DataTable({
        paging: true,
        pageLength: 10,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ مدخلات",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
            paginate: {
                first: "الأول",
                last: "الأخير",
                next: "التالي",
                previous: "السابق",
            },
            zeroRecords: "لا توجد سجلات مطابقة",
            emptyTable: "لا توجد بيانات في الجدول",
            search: "بحث:",
        },
        responsive: true,
        autoWidth: false,
    });
}

initDataTable("#pay1");
initDataTable("#pay2");

function submitForm(action, migrationId, fileName = "") {
    let form = document.getElementById("actionForm");
    if (!form) {
        console.error("الفورم المخفي غير موجود!");
        return;
    }
    let activeTab = document.querySelector("#payrollTabs .nav-link.active");
    if (activeTab) {
        localStorage.setItem("activeTab", activeTab.getAttribute("href"));
    }
    form.action = action;
    form.querySelector('input[name="migration_id"]').value = migrationId;
    Swal.fire({
        title: "جاري التنفيذ",
        html: `
            <div class="progress-container">
                <div class="file-name text-primary font-weight-bold mb-2">
                    <i class="fas fa-file-excel"></i> ${
                        fileName || "جارٍ معالجة الملف..."
                    }
                </div>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" style="width: 0%">
                    </div>
                </div>
                <div class="mt-2" id="progress-text">جاري تجهيز البيانات...</div>
            </div>
        `,
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            simulateProgress();
            form.submit();
        },
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("actionForm");
    if (form) {
        form.addEventListener("submit", function (e) {
            Swal.fire({
                title: "جاري المعالجة",
                html: `
                    <div class="progress-container">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 style="width: 0%">
                            </div>
                        </div>
                        <div class="mt-2" id="progress-text">جاري تجهيز البيانات...</div>
                    </div>
                `,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    simulateProgress();
                },
            });
        });
    }
});

function simulateProgress() {
    const progressBar = document.querySelector(".progress-bar");
    const progressText = document.getElementById("progress-text");
    const messages = [
        "جاري تجهيز البيانات...",
        "جاري تحليل الملف...",
        "جاري معالجة البيانات...",
        "جاري التحقق من البيانات...",
        "جاري حفظ البيانات...",
    ];
    const totalMinutes = Math.floor(Math.random() * 10) + 10;
    const totalMilliseconds = totalMinutes * 60 * 1000;
    const intervalTime = totalMilliseconds / 100;
    let width = 0;
    const interval = setInterval(() => {
        if (width >= 100) {
            clearInterval(interval);
        } else {
            if (width === 0) progressText.textContent = messages[0];
            else if (width === 5) progressText.textContent = messages[1];
            else if (width === 20) progressText.textContent = messages[2];
            else if (width === 75) progressText.textContent = messages[3];
            else if (width === 90) progressText.textContent = messages[4];
            width += 1;
            progressBar.style.width = width + "%";
        }
    }, intervalTime);
    const minutesText = document.createElement("div");
    minutesText.className = "mt-2 text-muted";
    document.querySelector(".progress-container").appendChild(minutesText);
    window.progressInterval = interval;
}

function confirmDelete(action, migrationId, fileName) {
    Swal.fire({
        title: "هل أنت متأكد؟",
        html: `
            <div class="mb-2 text-primary font-weight-bold">
                <i class="fas fa-file-excel"></i> ${fileName}
            </div>
            سيتم حذف الملف نهائياً ولا يمكن التراجع عن هذا الإجراء!
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8b5cf6",
        cancelButtonColor: "#ef4444",
        confirmButtonText: "نعم، قم بالحذف",
        cancelButtonText: "إلغاء",
        reverseButtons: true,
        customClass: {
            confirmButton: "btn btn-purple mr-4",
            cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
    }).then((result) => {
        if (result.isConfirmed) {
            submitForm(action, migrationId, fileName);
        }
    });
}

alertify.set("notifier", "position", "bottom-left");
alertify.set("notifier", "delay", 5);

document.addEventListener("DOMContentLoaded", function () {
    document
        .querySelectorAll('#payrollTabs a[data-toggle="tab"]')
        .forEach(function (tabLink) {
            tabLink.addEventListener("click", function (e) {
                e.preventDefault();
                $(this).tab("show");
                history.replaceState(null, null, " ");
            });
        });
});

document.addEventListener("DOMContentLoaded", function () {
    if (window.location.hash) {
        history.replaceState(
            null,
            null,
            window.location.pathname + window.location.search
        );
    }
});
/*******************************************************rndm details**************************************************************/

$(document).ready(function () {
    if (window.location.pathname.startsWith("/paie/rndm_details")) {
        if (window.payrollDefaults) {
            let defaultTrimestre = window.payrollDefaults.trimestre;
            let defaultYear = window.payrollDefaults.year;
            let defaultAdm = window.payrollDefaults.adm;
            let defaultAdmName = window.payrollDefaults.admName;

            if (defaultTrimestre && defaultYear && defaultAdm) {
                loadRndmDetails(
                    defaultTrimestre,
                    defaultYear,
                    defaultAdm,
                    defaultAdmName
                );
            }
        }
    }

    $(document).on("click", ".btn-admRndm", function () {
        let adm = $(this).data("adm");
        let admName = $(this).data("name");

        if (window.location.pathname.startsWith("/paie/rndm_details")) {
            if (window.payrollDefaults) {
                let defaultTrimestre = window.payrollDefaults.trimestre;
                let defaultYear = window.payrollDefaults.year;
                loadRndmDetails(defaultTrimestre, defaultYear, adm, admName);
            }
        }
    });
});

function loadRndmDetails(trimestre, year, adm, admName) {
    $("#report_department").text(admName);

    Swal.fire({
        title: "جارٍ تحميل البيانات...",
        text: "يرجى الانتظار",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
    });

    $.ajax({
        url: `/paie/rndmdetails/${trimestre}/${year}/${adm}`,
        method: "GET",
        dataType: "json",
        success: function (response) {
            Swal.close();

            if (response.rndmData && response.rndmData.length > 0) {
                $("#report_trimestre").text(response.trimestreName + " ");
                $("#report_year").text(response.year);

                let tableContent = response.rndmData
                    .map(
                        (data) => `
                <tr>
                    <td>${data.MATRI}</td>
                    <td>${data.Name}</td>
                    <td>${data.CATEG}<br>${data.ECH}</td>
                    <td>${data.JRPRIME}</td>
                    <td>${data.TAUX ?? "-"}</td>
                    <td>${data.SALBASE}</td>
                    <td>${data.BRUTSS}</td>
                    <td>${data.TOTGAIN}</td>
                    <td>${data.RETSS}</td>
                    <td>${data.RETITS}</td>
                    <td>${data.NETPAI}</td>
                </tr>
            `
                    )
                    .join("");

                $("#rndm_table_body").html(tableContent);
                $("#rndm_details").fadeIn();
                $("#printdetailsButton").fadeIn();

                if ($("#rndm_details").length) {
                    $("html, body").animate(
                        {
                            scrollTop: $("#rndm_details").offset().top - 100,
                        },
                        500
                    );
                }
            } else {
                Swal.fire({
                    icon: "info",
                    title: "لا توجد بيانات",
                    text: "لا توجد بيانات متاحة للفترة المحددة",
                    confirmButtonText: "حسنا",
                });
            }
        },
        error: function () {
            Swal.close();
            Swal.fire({
                icon: "error",
                title: "خطأ",
                text: "لا يوجد كشف تفصيلي للمردودية لهذا الثلاثي",
                confirmButtonText: "حسنا",
            });
        },
    });
}

$(document).on("click", "#printdetailsButton", function () {
    let printContent = document.getElementById(
        "rndm_report_content"
    )?.innerHTML;

    let newWindow = window.open("", "_blank");
    newWindow.document.open();
    newWindow.document.write(`
<html dir="rtl">
<head>
    <title>تقرير المردودية</title>
    <style>
        @page { size: landscape; margin: 0.3cm; }
        body { font-family: 'Cairo', sans-serif; font-size: 11px; margin: 0; padding: 3px; }
        h3, h2 { text-align: center; margin: 2px 0; line-height: 1.1; }
        h4 { margin: 0; line-height: 1.1; }
        .report-header { margin-bottom: 3px; }
        .report-title { margin: 3px 0; position: relative; }
        .report-title:after { content: ''; position: absolute; bottom: -2px; left: 50%; transform: translateX(-50%); width: 70px; height: 1px; background: #000; }
        table { width: 100%; border-collapse: collapse; margin: 3px auto; font-size: 8px; }
        th, td { border: 1px solid black; padding: 1px; text-align: center; }
        tr { line-height: 1; }
        th { background-color: #f2f2f2; font-weight: bold; }
        @media print {
            @page { size: landscape; margin: 0.3cm; }
            body { margin: 0; padding: 3px; }
            table { width: 100%; page-break-inside: auto; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }
    </style>
</head>
<body>
    ${printContent}
    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() { window.close(); }
        }
    </script>
</body>
</html>
`);
    newWindow.document.close();
});

/*********************************************************salary annual show********************************************************************* */
$(document).ready(function () {
    $("#searchFormAnnual").on("submit", function (e) {
        e.preventDefault();
        let searchQuery = $("#searchannual").val();
        let actionUrl = $(this).attr("action");

        Swal.fire({
            title: "جاري البحث...",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        $.ajax({
            url: actionUrl,
            method: $(this).attr("method"),
            data: $(this).serialize(),
            success: function (response) {
                Swal.close();

                if (
                    !response ||
                    response.trim() === "" ||
                    response.includes("لا توجد نتائج")
                ) {
                    Swal.fire({
                        icon: "info",
                        title: "لا توجد نتائج",
                        text: "لم يتم العثور على نتائج مطابقة للبحث",
                        confirmButtonText: "حسناً",
                    });
                    $("#payrollModal").modal("hide");
                } else {
                    let table = $("#payannual").DataTable();
                    table.clear();
                    table.destroy();
                    $("#payannual tbody").html(response);
                    $("#payannual").DataTable({
                        paging: true,
                        pageLength: 10,
                        language: {
                            searchPlaceholder: "بحث...",
                            sSearch: "",
                            lengthMenu: "عرض _MENU_ مدخلات",
                            info: "عرض _START_ إلى _END_ من _TOTAL_",
                            infoEmpty: "عرض 0 إلى 0 من 0 ",
                            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
                            paginate: {
                                first: "الأول",
                                last: "الأخير",
                                next: "التالي",
                                previous: "السابق",
                            },
                            zeroRecords: "لا توجد سجلات مطابقة",
                            emptyTable: "لا توجد بيانات في الجدول",
                            search: "بحث:",
                        },
                        responsive: true,
                        autoWidth: false,
                    });
                    $("#payrollModal").modal("hide");
                    Swal.fire({
                        icon: "success",
                        title: "تم العثور على النتائج",
                        text: "يمكنك الآن عرض كشف الراتب",
                        timer: 1500,
                        showConfirmButton: false,
                    });
                }
            },
            error: function () {
                Swal.close();
                Swal.fire({
                    icon: "error",
                    title: "خطأ",
                    text: "حدث خطأ أثناء البحث، يرجى المحاولة مرة أخرى",
                    confirmButtonText: "حسناً",
                });
            },
        });
    });
});
$(document).ready(function () {
    $(document).on("click", ".payroll-annual", function (e) {
        e.preventDefault();

        let matri = $(this).data("nccpf");
        let year = $("#year").val();

        Swal.fire({
            title: "جاري تحميل كشف الراتب...",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });

        $.ajax({
            url: `/paie/salary-annual/${matri}/${year}`,
            method: "GET",
            success: function (response) {
                Swal.close();
                $("#payroll-annual .card-body").html(response);
                $("#payroll-annual").fadeIn();

                $("html, body").animate(
                    {
                        scrollTop: $("#payroll-annual").offset().top - 50,
                    },
                    500
                );
            },
            error: function (xhr) {
                Swal.close();
                $("#payroll-annual").fadeOut();
                $("#payroll-annual .card-body").empty();

                let errorMessage = "حدث خطأ غير متوقع.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: "error",
                    title: "خطأ",
                    text: errorMessage,
                    confirmButtonText: "حسناً",
                });
            },
        });
    });
});

$(document).ready(function () {
    $("#printSlipannualButton").on("click", function () {
        let printContent = document.getElementById("salary-annual").innerHTML;
        let newWindow = window.open("", "_blank");
        newWindow.document.open();
        newWindow.document.write(`
<html dir="rtl">
	<head>
		<title>طباعة كشف الراتب</title>
		<style>
			@page {
				size: portrait;
				margin: 1cm;
			}
			body {
				direction: rtl;
				font-family: 'Cairo', sans-serif;
				text-align: right;
				margin: 20px;
				color: #1e293b;
			}
			
			table {
				width: 100%;
				border-collapse: collapse;
				margin-bottom: 20px;
			}
			
			th, td {
				border: 1px solid #64748b;
				padding: 8px;
				text-align: right;
			}
			
			th {
				background-color: #f1f5f9;
				font-weight: bold;
			}
			
			.details-table td {
				font-size: 12px;
				text-align: right;
				padding: 8px;
			}
			
			.text-center {
				text-align: center;
			}
			
			.highlight {
				font-weight: bold;
				background: #edeff7;
				border: 1px solid #ccc;
			}
		</style>
	</head>
	<body onload="window.print();">
		${printContent}
	</body>
</html>
`);
        newWindow.document.close();
        newWindow.focus();
        newWindow.print();
    });
});
/*******************************************************************absences settings*************************************************/
$(document).ready(function () {
    $("#flexSwitchCheckDefault").on("change", function () {
        if ($(this).is(":checked")) {
            $(this).next("label").text("مفتوح");
        } else {
            $(this).next("label").text("مغلق");
        }
    });

    $("#deleteModal").on("show.bs.modal", function (e) {
        var month = $("#clearMonth option:selected").text();
        var year = $("#clearYear").val();
        $("#deleteConfirmPeriod").text(month + " " + year);
    });

    $("#confirmDelete").on("click", function () {
        $("#clearForm").submit();
    });
});
/********************************************absences index******************************************************************************** */
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable("#absences-table")) {
        $("#absences-table").DataTable().destroy();
    }
    $("#absences-table").DataTable({
        lengthChange: true,
        pageLength: 10,
        ordering: false,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ في كل صفحة",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(تصفية من _MAX_ إجمالي المدخلات)",
            zeroRecords: "لم يتم العثور على سجلات مطابقة",
            paginate: {
                first: "الأول",
                previous: "السابق",
                next: "التالي",
                last: "الأخير",
            },
        },
    });
});
$(document).ready(function () {
    $("#absences-table tbody").on("click", "tr", function (e) {
        if (!$(e.target).is("input, button, i, select, option")) {
            $(this).toggleClass("selected");
        }
    });

    $(".absence_days, .absence_reason").on("change", function () {
        $(this).closest("tr").addClass("selected");

        if ($(this).hasClass("absence_days")) {
            let value = parseInt($(this).val());

            if (value < 0) {
                $(this).val(0);
                alertify.set("notifier", "position", "bottom-left");
                alertify.warning("عدد أيام الغياب لا يمكن أن يكون أقل من 0");
            }

            if (value > 30) {
                $(this).val(30);
                alertify.set("notifier", "position", "bottom-left");
                alertify.warning("عدد أيام الغياب لا يمكن أن يتجاوز 30 يوم");
            }
        }
    });

    $(".absence-reason-select").on("change", function () {
        let selectedValue = $(this).val();
        $(this).removeClass("justified-absence unjustified-absence");

        if (selectedValue === "غياب مبرر") {
            $(this).addClass("justified-absence");
        } else if (selectedValue === "غياب غير مبرر") {
            $(this).addClass("unjustified-absence");
        }
    });

    document.querySelectorAll(".adm-filter-btn").forEach((button) => {
        button.addEventListener("click", () => {
            const admValue = button.getAttribute("data-adm");
            const url = new URL(window.location.href);
            if (admValue) {
                url.searchParams.set("adm", admValue);
            } else {
                url.searchParams.delete("adm");
            }
            window.location.href = url.toString();
        });
    });

    $(".save-btn").on("click", function () {
        if (typeof saveAbsenceUrl === "undefined") {
            return;
        }
        let button = $(this);

        button.html('<i class="fas fa-spinner fa-spin"></i>');
        button.prop("disabled", true);

        let row = $(this).closest("tr");

        let matri = $(this).data("matri");
        let absenceDays = row.find("input.absence_days").val();
        let absenceReason = row.find("select.absence_reason").val();

        $.ajax({
            url: saveAbsenceUrl,
            type: "POST",
            data: {
                _token: csrfToken,
                MATRI: matri,
                absence_days: absenceDays,
                absence_reason: absenceReason,
                month: currentMonth,
                year: currentYear,
            },
            success: function (response) {
                if (response.success) {
                    alertify.set("notifier", "position", "bottom-left");
                    alertify.success(response.message);

                    row.removeClass("selected");

                    button.removeClass("btn-danger").addClass("btn-success");
                    button.html('<i class="fas fa-check"></i>');

                    setTimeout(function () {
                        button
                            .removeClass("btn-success")
                            .addClass("btn-danger");
                        button.html('<i class="fa-solid fa-floppy-disk"></i>');
                        button.prop("disabled", false);
                    }, 1000);
                } else {
                    alertify.error(response.message);
                    button.html('<i class="fa-solid fa-floppy-disk"></i>');
                    button.prop("disabled", false);
                }
            },
            error: function (xhr) {
                alertify.set("notifier", "position", "bottom-left");
                alertify.error("حدث خطأ أثناء حفظ البيانات.");

                button.html('<i class="fa-solid fa-floppy-disk"></i>');
                button.prop("disabled", false);
            },
        });
    });
});
/******************************************************absence create*************************************************************** */
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable("#absences-table1")) {
        $("#absences-table1").DataTable().destroy();
    }
    $("#absences-table1").DataTable({
        lengthChange: true,
        pageLength: 10,
        ordering: false,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ في كل صفحة",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(تصفية من _MAX_ إجمالي المدخلات)",
            zeroRecords: "لم يتم العثور على سجلات مطابقة",
            paginate: {
                first: "الأول",
                previous: "السابق",
                next: "التالي",
                last: "الأخير",
            },
        },
    });
});
$(document).ready(function () {
    $("#absences-table1 tbody").on("click", "tr", function (e) {
        if (!$(e.target).is("input, button, i, select, option")) {
            $(this).toggleClass("selected");
        }
    });

    $(".absence_days, .absence_reason").on("change", function () {
        $(this).closest("tr").addClass("selected");

        if ($(this).hasClass("absence_days")) {
            let value = parseInt($(this).val());

            if (value < 0) {
                $(this).val(0);
                alertify.set("notifier", "position", "bottom-left");
                alertify.warning("عدد أيام الغياب لا يمكن أن يكون أقل من 0");
            }

            if (value > 30) {
                $(this).val(30);
                alertify.set("notifier", "position", "bottom-left");
                alertify.warning("عدد أيام الغياب لا يمكن أن يتجاوز 30 يوم");
            }

            let row = $(this).closest("tr");
            if (value > 0) {
                row.removeClass("zero-absence");
            } else {
                row.addClass("zero-absence");
            }
        }
    });
    document.querySelectorAll(".adm-filter-btn").forEach((button) => {
        button.addEventListener("click", () => {
            const admValue = button.getAttribute("data-adm");
            const url = new URL(window.location.href);
            if (admValue) {
                url.searchParams.set("adm", admValue);
            } else {
                url.searchParams.delete("adm");
            }
            window.location.href = url.toString();
        });
    });
    $(".absence-reason-select").on("change", function () {
        let selectedValue = $(this).val();
        $(this).removeClass("justified-absence unjustified-absence");

        if (selectedValue === "غياب مبرر") {
            $(this).addClass("justified-absence");
        } else if (selectedValue === "غياب غير مبرر") {
            $(this).addClass("unjustified-absence");
        }
    });
});

$(document).ready(function () {
    $("#print-absences").on("click", function () {
        let table = $("#absences-table1").DataTable();
        let currentLength = table.page.len();
        table.page.len(-1).draw();

        setTimeout(function () {
            let employeeData = {};
            $("#absences-table1 tbody tr").each(function () {
                let matri = $(this).find("td:first").data("matri");
                let absenceDays = $(this).find("input.absence_days").val();
                let absenceReason = $(this).find("select.absence_reason").val();

                employeeData[matri] = {
                    absenceDays: absenceDays,
                    absenceReason: absenceReason,
                };
            });

            let printTableHtml = $("#absences-table1").prop("outerHTML");
            let $printTable = $(printTableHtml);

            $printTable.find("th:last-child, td:last-child").remove();
            $printTable.find("tbody tr").each(function () {
                let matri = $(this).find("td:first").data("matri");
                let absenceDays = employeeData[matri].absenceDays;
                let absenceReason = employeeData[matri].absenceReason;

                if (parseInt(absenceDays) === 0) {
                    $(this).remove();
                } else {
                    $(this).find("input.absence_days").replaceWith(absenceDays);
                    $(this)
                        .find("select.absence_reason")
                        .replaceWith(absenceReason);
                }
            });

            const arabicMonths = {
                1: "جانفي",
                2: "فيفري",
                3: "مارس",
                4: "أفريل",
                5: "ماي",
                6: "جوان",
                7: "جويلية",
                8: "أوت",
                9: "سبتمبر",
                10: "أكتوبر",
                11: "نوفمبر",
                12: "ديسمبر",
            };

            let monthLabel =
                arabicMonths[parseInt(currentMonth)] || "شهر غير معروف";
            let yearLabel = currentYear || "";

            let printWindow = window.open("", "_blank");
            printWindow.document.write(`
                <html dir="rtl">
                <head>
                    <title>قائمة الغيابات الشهرية للموظفين</title>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: "Times New Roman", serif; margin: 20px; direction: rtl; }
                        h2 { text-align: center; margin: 5px 0; }
                        h3 { margin: 5px 0; }
                        p { margin: 3px 0; }
                        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
                        th { background-color: #f2f2f2; }
                        .footer { margin-top: 40px; display: flex; justify-content: space-between; }
                        .signature { width: 200px; text-align: center; }
                        @media print {
                            button, .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <h2>الجمهورية الجزائرية الديمقراطية الشعبية</h2>
                    <h2>وزارة التربية الوطنية</h2>
                    <h3>مديرية التربية لولاية المغير</h3>
                    <h3>${authGroup}</h3>
                    <h2>قائمة الغيابات الشهرية للموظفين</h2>
                    <h2>${monthLabel} ${yearLabel}</h2>
                    <p>تاريخ الطباعة: ${new Date().toLocaleDateString(
                        "ar-DZ"
                    )}</p>

                    <div class="table-container">
                        ${$printTable[0].outerHTML}
                    </div>

                    <div class="footer">
                        <div class="signature"><p>إمضاء المصلحة</p></div>
                        <div class="signature"><p>مدير المؤسسة</p></div>
                    </div>
                </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.onload = function () {
                printWindow.focus();
                printWindow.print();
                table.page.len(currentLength).draw();
            };
        }, 500);
    });
});
/*********************************primescolerite index*********************************************************************************/
$(document).ready(function () {
    if ($.fn.DataTable.isDataTable("#primescolerite-table")) {
        $("#primescolerite-table").DataTable().destroy();
    }
    $("#primescolerite-table").DataTable({
        lengthChange: true,
        pageLength: 10,
        ordering: false,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ في كل صفحة",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(تصفية من _MAX_ إجمالي المدخلات)",
            zeroRecords: "لم يتم العثور على سجلات مطابقة",
            paginate: {
                first: "الأول",
                previous: "السابق",
                next: "التالي",
                last: "الأخير",
            },
        },
    });
});
$(document).ready(function () {
    // ✅ تحديد الصف عند الضغط (ما عدا عند الضغط على عناصر الإدخال)
    $("#primescolerite-table tbody").on("click", "tr", function (e) {
        if (!$(e.target).is("input, button, i, select, option")) {
            $(this).toggleClass("selected");
        }
    });

    // ✅ التعامل مع التغييرات في عدد الأبناء
    $(".nb_enf, .nb_enfsco").on("change", function () {
        $(this).closest("tr").addClass("selected");

        if ($(this).hasClass("nb_enf") || $(this).hasClass("nb_enfsco")) {
            let value = parseInt($(this).val(), 10) || 0;

            if (value < 0) {
                $(this).val(0);
                alertify.set("notifier", "position", "bottom-left");
                alertify.warning("عدد الاولاد لا يمكن أن يكون أقل من 0");
            }
        }
    });

    // ✅ تصفية حسب الإدارة
    document.querySelectorAll(".adm-filter-btn").forEach((button) => {
        button.addEventListener("click", () => {
            const admValue = button.getAttribute("data-adm");
            const url = new URL(window.location.href);

            if (admValue) {
                url.searchParams.set("adm", admValue);
            } else {
                url.searchParams.delete("adm");
            }

            window.location.href = url.toString();
        });
    });

    // ✅ زر الحفظ Ajax
    $(".save-btn").on("click", function () {
        if (typeof savePrimeUrl === "undefined") {
            return;
        }

        let button = $(this);
        let row = button.closest("tr");

        let matri = button.data("matri");
        let ENF = row.find("input.nb_enf").val() || 0;
        let ENFSCO = row.find("input.nb_enfsco").val() || 0;

        // إظهار أيقونة التحميل
        button.html('<i class="fas fa-spinner fa-spin"></i>').prop("disabled", true);

        $.ajax({
            url: savePrimeUrl,
            type: "POST",
            data: {
                _token: csrfToken,
                MATRI: matri,
                ENF: ENF,
                ENFSCO: ENFSCO,
                year: currentYear,
            },
            success: function (response) {
                if (response.success) {
                    alertify.set("notifier", "position", "bottom-left");
                    alertify.success(response.message);

                    row.removeClass("selected");

                    button.removeClass("btn-danger").addClass("btn-success")
                          .html('<i class="fas fa-check"></i>');

                    setTimeout(function () {
                        button.removeClass("btn-success")
                              .addClass("btn-danger")
                              .html('<i class="fa-solid fa-floppy-disk"></i>')
                              .prop("disabled", false);
                    }, 1000);
                } else {
                    alertify.error(response.message);
                    button.html('<i class="fa-solid fa-floppy-disk"></i>').prop("disabled", false);
                }
            },
            error: function () {
                alertify.set("notifier", "position", "bottom-left");
                alertify.error("حدث خطأ أثناء حفظ البيانات.");

                button.html('<i class="fa-solid fa-floppy-disk"></i>').prop("disabled", false);
            },
        });
    });

    // ✅ زر الطباعة
   $("#print-prime").on("click", function () {
    let table = $("#primescolerite-table").DataTable();
    let currentLength = table.page.len();
    table.page.len(-1).draw(); // عرض كل الصفوف قبل الطباعة

    setTimeout(function () {
        // ✅ تحديث القيم في DOM قبل النسخ
        $("#primescolerite-table tbody tr").each(function () {
            let $row = $(this);
            let ENF = $row.find("input.nb_enf").val() || 0;
            let ENFSCO = $row.find("input.nb_enfsco").val() || 0;

            // حط القيم داخل الـ input كـ attribute (عشان يبقا ظاهر في outerHTML)
            $row.find("input.nb_enf").attr("value", ENF);
            $row.find("input.nb_enfsco").attr("value", ENFSCO);
        });

        // ✅ الآن انسخ الجدول بعد تحديث القيم
        let printTableHtml = $("#primescolerite-table").prop("outerHTML");
        let $printTable = $(printTableHtml);

        // إزالة عمود العمليات (الأخير)
        $printTable.find("th:last-child, td:last-child").remove();

        // استبدال الـ input بالقيم النهائية
        $printTable.find("tbody tr").each(function () {
            let $row = $(this);
            let ENF = $row.find("input.nb_enf").val() || 0;
            let ENFSCO = $row.find("input.nb_enfsco").val() || 0;

            $row.find("input.nb_enf").replaceWith(ENF);
            $row.find("input.nb_enfsco").replaceWith(ENFSCO);
        });

        let yearLabel = currentYear || "";

        let printWindow = window.open("", "_blank");
        printWindow.document.write(`
            <html dir="rtl">
            <head>
                <title>قائمة منحة التمدرس</title>
                <meta charset="UTF-8">
                <style>
                    body { font-family: "Times New Roman", serif; margin: 20px; direction: rtl; }
                    h2 { text-align: center; margin: 5px 0; }
                    h3 { margin: 5px 0; }
                    p { margin: 3px 0; }
                    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                    th, td { border: 1px solid #000; padding: 6px; text-align: center; }
                    th { background-color: #f2f2f2; }
                    .footer { margin-top: 40px; display: flex; justify-content: space-between; }
                    .signature { width: 200px; text-align: center; }
                    @media print { button, .no-print { display: none; } }
                </style>
            </head>
            <body>
                <h2>الجمهورية الجزائرية الديمقراطية الشعبية</h2>
                <h2>وزارة التربية الوطنية</h2>
                <h3>مديرية التربية لولاية المغير</h3>
                <h3>${authGroup}</h3>
                <h2>قائمة الموظفين المعنيين بمنحة التمدرس</h2>
                <h2>للسنة ${yearLabel}</h2>
                <p>تاريخ الطباعة: ${new Date().toLocaleDateString("ar-DZ")}</p>
                <div class="table-container">
                    ${$printTable[0].outerHTML}
                </div>
                <div class="footer">
                    <div class="signature"><p>إمضاء المصلحة</p></div>
                    <div class="signature"><p>مدير المؤسسة</p></div>
                </div>
            </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
            table.page.len(currentLength).draw(); // استرجاع الإعداد الأصلي
        };
    }, 500);
});

});


/*****************************************************message create*****************************************************************************************/

$(document).ready(function () {
    if ($("#summernote").length) {
        $("#summernote").summernote({
            height: 100,
            lang: "ar-AR",
            toolbar: [
                ["style", ["bold", "italic", "underline", "clear"]],
                ["fontsize", ["fontsize"]],
                ["color", ["color"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["insert", ["link", "picture", "video", "table"]],
                ["view", ["fullscreen"]],
            ],
        });
    }

    if ($.fn.DataTable.isDataTable("#fileTable")) {
        $("#fileTable").DataTable().destroy();
    }
    $("#fileTable").DataTable({
        paging: true,
        pageLength: 10,
        language: {
            searchPlaceholder: "بحث...",
            sSearch: "",
            lengthMenu: "عرض _MENU_ مدخلات",
            info: "عرض _START_ إلى _END_ من _TOTAL_",
            infoEmpty: "عرض 0 إلى 0 من 0 ",
            infoFiltered: "(منتقاة من _MAX_ إجمالي المدخلات)",
            paginate: {
                first: "الأول",
                last: "الأخير",
                next: "التالي",
                previous: "السابق",
            },
            zeroRecords: "لا توجد سجلات مطابقة",
            emptyTable: "لا توجد بيانات في الجدول",
            search: "بحث:",
        },
        responsive: true,
        autoWidth: false,
    });
});
/***************************************** * إدارة الرسائل *****************************************/
document.addEventListener("DOMContentLoaded", function () {
    /****************************************** حفظ الرسائل (Starred) *****************************************/
    document.querySelectorAll(".save-message").forEach((button) => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();

            const messageId = this.dataset.messageId;
            const btn = this;

            fetch("/messages/save", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: JSON.stringify({ message_id: messageId }),
            })
                .then((res) => res.json())
                .then((data) => {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "bottom-start",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });

                    if (data.success) {
                        btn.classList.toggle("starred");
                        Toast.fire({
                            icon: "success",
                            title: data.message,
                        });
                    } else {
                        Toast.fire({
                            icon: "error",
                            title: data.message,
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: "error",
                        text: "حدث خطأ أثناء محاولة حفظ الرسالة.",
                    });
                });
        });
    });

    /****************************************** استعادة الرسائل المحفوظة *****************************************/
    document.querySelectorAll(".restore-message").forEach((button) => {
        button.addEventListener("click", function () {
            const messageId = this.dataset.messageId;

            fetch(window.appRoutes.restoreSaved, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": window.csrfToken,
                },
                body: JSON.stringify({ message_id: messageId }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "تمت الاستعادة!",
                            text: data.message,
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({ icon: "error", text: data.message });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: "error",
                        text: "حدث خطأ أثناء محاولة الاستعادة.",
                    });
                });
        });
    });

    /****************************************** الحذف (Inbox & Saved) *****************************************/
    const deleteButton = document.getElementById("deleteButton");
    const checkboxes = document.querySelectorAll('input[name="message_ids[]"]');
    const checkAll = document.getElementById("checkAll");

    function toggleDeleteButton() {
        deleteButton.disabled = !Array.from(checkboxes).some((c) => c.checked);
    }

    if (deleteButton && checkboxes.length) {
        checkboxes.forEach((c) =>
            c.addEventListener("change", toggleDeleteButton)
        );
        toggleDeleteButton();

        if (checkAll) {
            checkAll.addEventListener("change", function () {
                checkboxes.forEach((c) => (c.checked = this.checked));
                toggleDeleteButton();
            });
        }

        deleteButton.addEventListener("click", function (event) {
            event.preventDefault();
            Swal.fire({
                title: "هل أنت متأكد من حذف الرسالة؟",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "نعم، احذفها",
                cancelButtonText: "إلغاء",
            }).then((result) => {
                if (result.isConfirmed) {
                    const form =
                        document.getElementById("messagesForm") ||
                        document.getElementById("deleteMessagesForm");
                    if (form) form.submit();
                }
            });
        });
    }
});

/*********************************************************show message details*********************************************************** */
document.addEventListener("DOMContentLoaded", function () {
    const printBtn = document.getElementById("printDetailsButton");

    if (printBtn) {
        printBtn.addEventListener("click", function () {
            const senderGroup = this.dataset.senderGroup;
            const receiverGroups = JSON.parse(this.dataset.receiverGroups);
            const formattedDate = this.dataset.date;
            const subject = this.dataset.subject;
            const bodyContent = this.dataset.body;
            const attachments = JSON.parse(this.dataset.attachments);

            const readTable = document.getElementById("readTable");
            const readTableHTML = readTable
                ? readTable.outerHTML
                : "<p>لا يوجد جدول اطلاع</p>";

            const detailsContent = `
                <!DOCTYPE html>
                <html dir="rtl" lang="ar">
                <head> 
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>تقرير الرسالة</title>
                <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
                <style>
    body {
      font-family: 'Cairo', sans-serif;
      margin: 0;
      padding: 0;
      background: #f8fafc;
      color: #1e293b;
    }
    .container {
      max-width: 900px;
      margin: 10px auto;
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      padding: 10px;
    }
    .header {
      text-align: center;
      margin-bottom: 5px;
    }
    .header h1 {
      margin: 0;
      font-size: 26px;
      color: #0f172a;
    }
    .subtitle {
      color: #64748b;
      font-size: 15px;
    }
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-bottom: 0px;
    }
    .info-card {
      background: #f1f5f9;
      padding: 5px;
      border-radius: 12px;
    }
    .info-label {
      font-weight: 600;
      color: #475569;
      margin-bottom: 5px;
    }
    .info-value {
      color: #0f172a;
    }
    .message-body, .attachments, .read-table-container {
      margin-bottom: 25px;
    }
    h4 {
      margin-bottom: 5px;
      color: #0f172a;
      border-bottom: 2px solid #e2e8f0;
      padding-bottom: 2px;
    }
    #attachments-list li {
      margin: 5px 0;
    }
    #read-table table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 2px;
    }
    #read-table th, #read-table td {
      border: 1px solid #e2e8f0;
      padding: 0px;
      text-align: center;
    }
    #read-table th {
      background: #f1f5f9;
    }
    .print-button {
      display: block;
      margin: 0 auto;
      padding: 12px 25px;
      background: #2563eb;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }
    .print-button:hover {
      background: #1e40af;
    }
    @media print {
      .print-button {
        display: none;
      }
      body {
        background: white;
      }
      .container {
        box-shadow: none;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>📧 تقرير الرسالة</h1>
      <p class="subtitle">تفاصيل شاملة عن الرسالة المرسلة</p>
    </div>
    
    <div class="content">
      <div class="info-grid">
        <div class="info-card">
          <div class="info-label">المرسل</div>
          <div class="info-value">${senderGroup || "غير محدد"}</div>
        </div>
        <div class="info-card">
          <div class="info-label">المرسل إليه</div>
          <div class="info-value">${
              receiverGroups && receiverGroups.length > 0
                  ? receiverGroups.join(" • ")
                  : "غير محدد"
          }</div>
        </div>
        <div class="info-card">
          <div class="info-label">تاريخ الإرسال</div>
          <div class="info-value">${formattedDate || "غير محدد"}</div>
        </div>
        <div class="info-card">
          <div class="info-label">الموضوع</div>
          <div class="info-value">${subject || "بدون موضوع"}</div>
        </div>
      </div>

      <div class="message-body">
        <h4>📝 نص الرسالة</h4>
        <div>${bodyContent || "لا يوجد محتوى"}</div>
      </div>

      <div class="attachments">
        <h4>📎 المرفقات (${
            attachments && attachments.length > 0 ? attachments.length : 0
        })</h4>
        <ul id="attachments-list">
          ${
              attachments && attachments.length > 0
                  ? attachments.map((att) => `<li>📄 ${att}</li>`).join("")
                  : '<li style="color: #64748b; font-style: italic;">لا توجد مرفقات</li>'
          }
        </ul>
      </div>

      <div class="read-table-container">
        <h4>📊 جدول حالة الاطلاع</h4>
        <div id="read-table">
          ${
              readTableHTML ||
              '<p style="padding: 20px; text-align: center; color: #64748b;">لا توجد بيانات للعرض</p>'
          }
        </div>
      </div>

      <button class="print-button" onclick="window.print()">🖨️ طباعة التقرير</button>
    </div>
  </div>
</body>
</html>

            `;
            const printWindow = window.open("", "_blank");
            printWindow.document.write(detailsContent);
            printWindow.document.close();
            printWindow.print();
        });
    }
});
/**************************************************message trash*********************************************************************** */
document.addEventListener("DOMContentLoaded", function () {
    const permanentlyDeleteButton = document.getElementById(
        "permanentlyDeleteButton"
    );
    const restoreButton = document.getElementById("restoreButton");
    const checkboxes = document.querySelectorAll('input[name="message_ids[]"]');
    const checkAll = document.getElementById("checkAll");

    function toggleButtons() {
        const anyChecked = Array.from(checkboxes).some(
            (checkbox) => checkbox.checked
        );
        if (permanentlyDeleteButton)
            permanentlyDeleteButton.disabled = !anyChecked;
        if (restoreButton) restoreButton.disabled = !anyChecked;
    }

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", toggleButtons);
    });

    if (checkAll) {
        checkAll.addEventListener("change", function () {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
            toggleButtons();
        });
    }

    if (restoreButton) {
        restoreButton.addEventListener("click", function (event) {
            event.preventDefault();
            Swal.fire({
                title: "هل أنت متأكد من استعادة الرسائل المحددة؟",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "نعم، استعد!",
                cancelButtonText: "إلغاء",
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById("messagesForm");
                    form.action = restoreRoute;
                    form.method = "POST";
                    form.submit();
                }
            });
        });
    }

    if (permanentlyDeleteButton) {
        permanentlyDeleteButton.addEventListener("click", function (event) {
            event.preventDefault();
            Swal.fire({
                title: "هل أنت متأكد من الحذف النهائي للرسالة؟",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "نعم، احذف!",
                cancelButtonText: "إلغاء",
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById("messagesForm");
                    const selectedIds = Array.from(checkboxes)
                        .filter((checkbox) => checkbox.checked)
                        .map((checkbox) => checkbox.value);

                    form.querySelectorAll(
                        'input[name="message_ids[]"]'
                    ).forEach((input) => input.remove());

                    selectedIds.forEach((id) => {
                        const hiddenInput = document.createElement("input");
                        hiddenInput.type = "hidden";
                        hiddenInput.name = "message_ids[]";
                        hiddenInput.value = id;
                        form.appendChild(hiddenInput);
                    });

                    form.action = permanentlyDeleteRoute;

                    const methodInput = document.createElement("input");
                    methodInput.type = "hidden";
                    methodInput.name = "_method";
                    methodInput.value = "DELETE";
                    form.appendChild(methodInput);

                    form.submit();
                }
            });
        });
    }
});
