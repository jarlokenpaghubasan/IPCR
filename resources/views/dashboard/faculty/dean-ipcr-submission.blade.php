<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dean IPCR Submission - {{ $submission->title }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/urs_logo.jpg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
    <nav class="bg-white shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/urs_logo.jpg') }}" alt="URS Logo" class="h-10 w-auto object-contain">
                <div>
                    <h1 class="text-base sm:text-lg font-bold">Dean IPCR Submission</h1>
                    <p class="text-xs text-gray-500">HR View</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a id="deanIpcrBackLink" href="{{ route('faculty.summary-reports', ['category' => 'dean-ipcrs', 'department' => 'all']) }}" onclick="return closeDeanIpcrTab(event, this)" class="px-3 py-2 rounded-md text-xs font-semibold border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">Back</a>
                <a href="{{ route('faculty.summary-reports.dean-ipcrs.export', ['submission' => $submission->id]) }}" class="px-3 py-2 rounded-md text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">Export</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8 space-y-6">
        <section class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $submission->title }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $submission->user?->name ?? 'Unknown Dean' }}{{ $submission->user?->employee_id ? ' · ' . $submission->user->employee_id : '' }}</p>
                </div>
                @if($latestCalibration)
                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-green-50 border border-green-200">
                    <span class="text-xs font-semibold uppercase tracking-wider text-green-700">Latest Calibrated Score</span>
                    <span class="text-base font-bold text-green-700">{{ number_format($latestCalibration->overall_score, 2) }}</span>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-5 text-sm">
                <div class="bg-gray-50 rounded-md p-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wider">Department</div>
                    <div class="font-semibold mt-1">{{ $submission->user?->department?->code ?? $submission->user?->department?->name ?? 'N/A' }}</div>
                </div>
                <div class="bg-gray-50 rounded-md p-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wider">School Year</div>
                    <div class="font-semibold mt-1">{{ $submission->school_year }}</div>
                </div>
                <div class="bg-gray-50 rounded-md p-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wider">Semester</div>
                    <div class="font-semibold mt-1">{{ $submission->semester }}</div>
                </div>
                <div class="bg-gray-50 rounded-md p-3">
                    <div class="text-xs text-gray-500 uppercase tracking-wider">Submitted</div>
                    <div class="font-semibold mt-1">{{ $submission->submitted_at?->format('M d, Y h:i A') ?? 'N/A' }}</div>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 sm:p-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-3">Calibration History</h3>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[620px] text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border border-gray-200">
                            <th class="px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border border-gray-200">Calibrated By</th>
                            <th class="px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border border-gray-200">Score</th>
                            <th class="px-3 py-2 text-left text-[11px] font-bold uppercase tracking-wider text-gray-500 border border-gray-200">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calibrationHistory as $item)
                        <tr class="border border-gray-200">
                            <td class="px-3 py-2 border border-gray-200">{{ $item['dean_name'] }}</td>
                            <td class="px-3 py-2 border border-gray-200 font-semibold">{{ number_format($item['overall_score'], 2) }}</td>
                            <td class="px-3 py-2 border border-gray-200">{{ $item['updated_at']?->format('M d, Y h:i A') ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-3 py-6 text-center text-sm text-gray-500 border border-gray-200">No calibration history found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 sm:p-6">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 mb-3">IPCR Content</h3>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] text-sm border-collapse" id="deanIpcrTable">
                    <thead>
                        <tr class="bg-gray-100 border border-gray-300">
                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" rowspan="2" style="width: 15%;">MFO</th>
                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" rowspan="2" style="width: 25%;">Success Indicators</th>
                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" rowspan="2" style="width: 20%;">Actual Accomplishments</th>
                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" colspan="4">Rating</th>
                            <th class="border border-gray-300 px-3 py-2 text-xs font-bold text-gray-700" rowspan="2" style="width: 15%;">Remarks</th>
                        </tr>
                        <tr class="bg-gray-100 border border-gray-300">
                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600">Q</th>
                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600">E</th>
                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600">T</th>
                            <th class="border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-600">A</th>
                        </tr>
                    </thead>
                    <tbody>
                        {!! $submission->table_body_html !!}
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <div id="soDocsModal" class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/60" onclick="closeSoDocsModal()"></div>
        <div class="relative mx-auto mt-6 sm:mt-10 w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden z-10">
            <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <h3 id="soDocsTitle" class="text-sm font-bold text-gray-900 truncate"></h3>
                    <p id="soDocsDesc" class="text-xs text-gray-500 truncate mt-0.5"></p>
                </div>
                <button onclick="closeSoDocsModal()" class="text-gray-400 hover:text-gray-600 ml-3 flex-shrink-0 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div id="soDocsList" class="px-5 py-4 max-h-[65vh] overflow-y-auto bg-slate-50/60">
                <div class="text-center py-8 text-sm text-gray-400">Loading documents...</div>
            </div>

            <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button onclick="closeSoDocsModal()" class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">Close</button>
            </div>
        </div>
    </div>

    <div id="docPreviewModal" class="fixed inset-0 z-[80] hidden">
        <div class="absolute inset-0 bg-black/70" onclick="closeDocPreviewModal()"></div>
        <div class="relative mx-auto mt-4 sm:mt-8 w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden z-10">
            <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <h3 id="docPreviewTitle" class="text-sm sm:text-base font-bold text-gray-900 truncate">Document Preview</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Image preview</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="closeDocPreviewModal()" class="text-gray-400 hover:text-gray-600 ml-1 flex-shrink-0 p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="bg-slate-100">
                <iframe id="docPreviewFrame" title="Document preview" class="w-full h-[75vh] border-0"></iframe>
            </div>

            <div class="px-5 py-2.5 bg-gray-50 border-t border-gray-200 text-[11px] text-gray-500">
                Close this window to return to supporting documents.
            </div>
        </div>
    </div>

    <script>
        function closeDeanIpcrTab(event, linkEl) {
            if (event) {
                event.preventDefault();
            }

            var fallbackUrl = (linkEl && linkEl.href) ? linkEl.href : '{{ route('faculty.summary-reports', ['category' => 'dean-ipcrs', 'department' => 'all']) }}';

            if (window.opener && !window.opener.closed) {
                try {
                    window.opener.focus();
                } catch (_) {
                    // Ignore focus errors from browser policies.
                }

                window.close();

                // Fallback for browsers that block close in this context.
                setTimeout(function () {
                    if (!window.closed) {
                        window.location.href = fallbackUrl;
                    }
                }, 250);

                return false;
            }

            window.location.href = fallbackUrl;
            return false;
        }

        const supportingDocumentsBySo = @json(($supportingDocuments ?? collect())->toArray());
        let activeSoDocuments = [];

        function escapeHtml(value) {
            return (value || '').toString()
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function normalizeSoLabel(label) {
            var value = (label || '').toString().toUpperCase().replace(/\s+/g, ' ').trim();
            var match = value.match(/\bSO\s*([A-Z0-9IVXLCM]+)\b/);

            if (match) {
                return 'SO ' + match[1];
            }

            if (value.indexOf(':') !== -1) {
                return value.split(':')[0].trim();
            }

            return value;
        }

        function buildSupportingDocIndex() {
            var indexed = {};

            Object.keys(supportingDocumentsBySo || {}).forEach(function (rawLabel) {
                var normalizedKey = normalizeSoLabel(rawLabel);

                if (!indexed[normalizedKey]) {
                    indexed[normalizedKey] = [];
                }

                indexed[normalizedKey] = indexed[normalizedKey].concat(supportingDocumentsBySo[rawLabel] || []);
            });

            return indexed;
        }

        const supportingDocIndex = buildSupportingDocIndex();

        function getSoRowMeta(row) {
            var firstCell = row.querySelector('td');
            var rawText = firstCell ? (firstCell.innerText || firstCell.textContent || '') : '';
            rawText = rawText.replace(/\s+/g, ' ').trim();

            var label = rawText;
            var description = '';

            if (rawText.indexOf(':') !== -1) {
                var parts = rawText.split(':');
                label = (parts.shift() || '').trim();
                description = parts.join(':').trim();
            }

            var spanLabel = row.querySelector('span.font-semibold.text-gray-800');
            if (spanLabel && spanLabel.textContent) {
                label = spanLabel.textContent.trim().replace(/:$/, '');
            }

            var soInput = row.querySelector('input[type="text"]');
            if (soInput && soInput.value) {
                description = soInput.value;
            }

            return {
                key: normalizeSoLabel(label || rawText),
                label: label || rawText || 'Supporting Documents',
                description: description,
            };
        }

        function getDocumentType(doc) {
            var mime = ((doc.mime_type || '') + '').toLowerCase();
            var name = ((doc.original_name || '') + '').toLowerCase();

            if (mime.indexOf('image/') === 0 || /\.(jpg|jpeg|png|gif|bmp|webp|svg)$/i.test(name)) {
                return 'image';
            }

            if (mime.indexOf('pdf') !== -1 || /\.pdf$/i.test(name)) {
                return 'pdf';
            }

            if (
                mime.indexOf('word') !== -1 ||
                mime.indexOf('officedocument.wordprocessingml') !== -1 ||
                /\.(doc|docx|rtf)$/i.test(name)
            ) {
                return 'word';
            }

            if (
                mime.indexOf('excel') !== -1 ||
                mime.indexOf('spreadsheetml') !== -1 ||
                /\.(xls|xlsx|csv)$/i.test(name)
            ) {
                return 'sheet';
            }

            if (
                mime.indexOf('powerpoint') !== -1 ||
                mime.indexOf('presentationml') !== -1 ||
                /\.(ppt|pptx)$/i.test(name)
            ) {
                return 'slide';
            }

            return 'document';
        }

        function getDocumentTypeUi(docType) {
            if (docType === 'image') {
                return { label: 'Image', className: 'bg-green-100 text-green-700', icon: 'IMG' };
            }

            if (docType === 'pdf') {
                return { label: 'PDF', className: 'bg-red-100 text-red-700', icon: 'PDF' };
            }

            if (docType === 'word') {
                return { label: 'DOCX', className: 'bg-blue-100 text-blue-700', icon: 'DOC' };
            }

            if (docType === 'sheet') {
                return { label: 'Sheet', className: 'bg-emerald-100 text-emerald-700', icon: 'XLS' };
            }

            if (docType === 'slide') {
                return { label: 'Slides', className: 'bg-orange-100 text-orange-700', icon: 'PPT' };
            }

            return { label: 'Document', className: 'bg-slate-100 text-slate-700', icon: 'DOC' };
        }

        function getPreviewButtonLabel(docType) {
            if (docType === 'image') {
                return 'View Image';
            }

            if (docType === 'pdf') {
                return 'View PDF';
            }

            if (docType === 'word') {
                return 'View DOCX';
            }

            if (docType === 'sheet') {
                return 'View Sheet';
            }

            if (docType === 'slide') {
                return 'View Slides';
            }

            return 'Preview';
        }

        function resolveDownloadUrl(doc) {
            var rawUrl = (doc.path || '').toString().trim();
            if (!rawUrl) {
                return '#';
            }

            // Force attachment disposition for Cloudinary assets.
            if (rawUrl.indexOf('/upload/') !== -1) {
                return rawUrl.replace('/upload/', '/upload/fl_attachment/');
            }

            return rawUrl;
        }

        function resolveBrowserViewUrl(doc) {
            var rawUrl = (doc.path || '').toString().trim();
            if (!rawUrl) {
                return '#';
            }

            var type = getDocumentType(doc);

            // Use browser-friendly web viewers for Office formats.
            if (type === 'word' || type === 'sheet' || type === 'slide') {
                return 'https://docs.google.com/gview?url=' + encodeURIComponent(rawUrl) + '&embedded=true';
            }

            // PDFs and images can be shown directly by browser preview features.
            return rawUrl;
        }

        function openDocPreviewForIndex(index) {
            var doc = activeSoDocuments[index];
            if (!doc) {
                return;
            }

            var docType = getDocumentType(doc);
            if (docType !== 'image') {
                return;
            }

            var previewUrl = resolveBrowserViewUrl(doc);
            var frame = document.getElementById('docPreviewFrame');
            var titleEl = document.getElementById('docPreviewTitle');
            var modal = document.getElementById('docPreviewModal');

            if (titleEl) {
                titleEl.textContent = doc.original_name || 'Document Preview';
            }

            if (frame) {
                frame.src = previewUrl;
            }

            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        window.closeDocPreviewModal = function () {
            var modal = document.getElementById('docPreviewModal');
            var frame = document.getElementById('docPreviewFrame');

            if (frame) {
                frame.src = 'about:blank';
            }

            if (modal) {
                modal.classList.add('hidden');
            }
        };

        function renderSoDocuments(documents) {
            var container = document.getElementById('soDocsList');
            if (!container) {
                return;
            }

            activeSoDocuments = documents || [];

            if (!documents || documents.length === 0) {
                container.innerHTML = '<div class="text-center py-8"><p class="text-sm text-gray-400">No supporting documents for this SO.</p></div>';
                return;
            }

            container.innerHTML = documents.map(function (doc, index) {
                var safeName = escapeHtml(doc.original_name || 'Document');
                var nameDisplay = safeName.length > 45 ? safeName.substring(0, 42) + '...' : safeName;
                var meta = [];
                var type = getDocumentType(doc);
                var typeUi = getDocumentTypeUi(type);
                var previewLabel = getPreviewButtonLabel(type);
                var downloadUrl = resolveDownloadUrl(doc);
                var isImage = type === 'image';
                var isDownloadOnly = !isImage;
                var thumbOrIcon = isImage
                    ? '<img src="' + escapeHtml(doc.path || '') + '" alt="' + safeName + '" class="w-11 h-11 rounded-lg object-cover border border-slate-200 shrink-0" loading="lazy">'
                    : '<div class="w-11 h-11 rounded-lg bg-slate-100 border border-slate-200 text-[11px] font-bold text-slate-600 flex items-center justify-center shrink-0">' + typeUi.icon + '</div>';

                if (doc.file_size_human) {
                    meta.push(doc.file_size_human);
                }

                if (doc.created_at_display) {
                    meta.push(doc.created_at_display);
                }

                return '<div class="bg-white border border-slate-200 rounded-xl p-3 mb-3 shadow-sm hover:shadow-md transition-shadow">' +
                    '<div class="flex items-start gap-3">' +
                        thumbOrIcon +
                        '<div class="flex-1 min-w-0">' +
                            '<p class="text-sm font-semibold text-gray-800 truncate" title="' + safeName + '">' + nameDisplay + '</p>' +
                            '<p class="text-xs text-gray-400 mt-0.5">' + (meta.join(' · ') || 'Cloudinary file') + '</p>' +
                            '<div class="mt-2 flex items-center gap-2">' +
                                '<span class="inline-flex px-2 py-0.5 text-[10px] font-semibold rounded-full ' + typeUi.className + '">' + typeUi.label + '</span>' +
                                (isDownloadOnly ? '<span class="inline-flex px-2 py-0.5 text-[10px] font-semibold rounded-full bg-amber-100 text-amber-700">Download only</span>' : '') +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="mt-3 flex items-center justify-end gap-2">' +
                        (isImage
                            ? '<button type="button" onclick="openDocPreviewForIndex(' + index + ')" class="px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">' + previewLabel + '</button>'
                            : '<a href="' + downloadUrl + '" download class="px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">Download</a>') +
                    '</div>' +
                '</div>';
            }).join('');
        }

        function openSoDocsModal(title, description, documents) {
            var modal = document.getElementById('soDocsModal');
            var titleEl = document.getElementById('soDocsTitle');
            var descEl = document.getElementById('soDocsDesc');

            if (titleEl) {
                titleEl.textContent = title || 'Supporting Documents';
            }

            if (descEl) {
                descEl.textContent = description || '';
            }

            renderSoDocuments(documents || []);

            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function attachSoDocumentTriggers() {
            var tableBody = document.querySelector('#deanIpcrTable tbody');
            if (!tableBody) {
                return;
            }

            tableBody.querySelectorAll('tr.bg-blue-100').forEach(function (row) {
                var meta = getSoRowMeta(row);
                var docs = supportingDocIndex[meta.key] || [];

                row.style.cursor = 'pointer';
                row.title = 'Click to view supporting documents';

                var badge = row.querySelector('.so-doc-badge');
                if (!badge) {
                    var firstCell = row.querySelector('td');
                    if (firstCell) {
                        badge = document.createElement('span');
                        badge.className = 'so-doc-badge ml-2 inline-flex items-center text-[11px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full';
                        badge.innerHTML = '<span class="so-doc-count">0</span>';
                        var target = firstCell.querySelector('div.flex') || firstCell;
                        target.appendChild(badge);
                    }
                }

                if (badge) {
                    var countEl = badge.querySelector('.so-doc-count');
                    if (countEl) {
                        countEl.textContent = docs.length + ' doc' + (docs.length === 1 ? '' : 's');
                    }
                }

                row.addEventListener('click', function (event) {
                    if (event.target && event.target.closest('a,button,input,textarea,select')) {
                        return;
                    }

                    openSoDocsModal(meta.label, meta.description, docs);
                });
            });
        }

        window.closeSoDocsModal = function () {
            var modal = document.getElementById('soDocsModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        };

        // Make this view strictly read-only.
        document.querySelectorAll('#deanIpcrTable input, #deanIpcrTable textarea, #deanIpcrTable select, #deanIpcrTable button').forEach(function (el) {
            el.setAttribute('disabled', 'disabled');
            el.classList.add('cursor-not-allowed', 'opacity-90');
        });

        attachSoDocumentTriggers();
    </script>
</body>
</html>
