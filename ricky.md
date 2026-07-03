# Project Update

Date: 03/07/2026
Time: 23:00

## Student Registration Form Improvements

- Fixed validation behavior so users are returned to the relevant registration step instead of always being sent back to Step 1.
- Preserved previously entered form data after validation errors, including exam selections, practice exams, accommodation status, and accommodation rows.
- Added browser-side draft autosave so text fields, selects, checkboxes, radio buttons, and the current step are restored after a page refresh.
- Added temporary passport draft upload support so selected passport files can remain available after validation errors and page refreshes.
- Added a dedicated passport draft upload endpoint for the registration form.
- Updated the final registration submission flow so it can use either a newly uploaded passport file or a previously saved passport draft.
- Replaced blocking JavaScript alerts with inline toast notifications for registration form messages.(tampilan alert e diganti)
- Improved passport file display in the review step so it shows the saved draft file name when available.
- Added checks to make sure the form returns to the correct step, keeps passport draft uploads, and handles draft passport uploads correctly.

