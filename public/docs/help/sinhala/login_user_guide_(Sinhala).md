# Login System: පරිශීලක අත්පොත & ක්‍රියාවලි වාර්තාව

## හැඳින්වීම (Introduction)
මෙම ලේඛනය Resource Booking System හි **Login Process** (පිවිසුම් ක්‍රියාවලිය) විස්තර කරයි. එය Authentication (අවසර ලබා ගැනීම), Validation Rules (වලංගුකරණ රෙගුලාසි), සහ User Roles (පරිශීලක භූමිකාවන්) මත පදනම් වූ Redirection Logic විස්තර කරයි.

---

## 1 වන අදියර: Login පිටුවට පිවිසීම
**URL:** `/login`

1.  **Navigation (ගමන් මග):**
    *   Homepage හි ඇති "Log In" සබැඳිය (link) හරහා පරිශීලකයින්ට මෙම පිටුවට පිවිසිය හැක.
    *   URL එක හරහා සෘජුවම පිවිසිය හැක.
2.  **Interface (අතුරු මුහුණත):**
    *   පෙර පිටුවට යාම සඳහා "Back" බොත්තමක් සහිත පිරිසිදු අතුරු මුහුණතක් මෙහි ඇත.
    *   **NIC** සහ **Passcode** ඇතුළත් කිරීම සඳහා Input Fields ඇත.

## 2 වන අදියර: Credentials & Submission
**Form Fields (පිටුවේ ඇති කොටස්):**

1.  **NIC (National Identity Card) Number:**
    *   *වර්ගය:* Text
    *   *අවශ්‍යතාවය:* අනිවාර්යයි (Yes)
    *   *විස්තරය:* නිලධාරියාගේ අනන්‍යතාවය තහවුරු කරන අංකය.
2.  **Password:**
    *   *වර්ගය:* Password
    *   *අවශ්‍යතාවය:* අනිවාර්යයි (Yes)
    *   *විස්තරය:* පරිශීලක ගිණුමට අදාළ ආරක්ෂිත මුරපදය.

**Client-Side Validation:**
*   JavaScript මගින් ඇතුළත් කරන දත්ත නිරීක්ෂණය කරනු ලැබේ.
*   NIC සහ Password යන දෙකම ඇතුළත් කරන තෙක් **Login** බොත්තම **Disabled** (අක්‍රියව) පවතී.
*   දත්ත ඇතුළත් කළ පසු, බොත්තම **Enabled** (සක්‍රිය) වන අතර එහි වර්ණය වෙනස් වේ.

## 3 වන අදියර: Redirection & Access Control
පරිශීලකයාගේ **Role** එක මත පදනම්ව System එක ඔහු/ඇයව අදාළ පිටුවට යොමු කරයි:

*   **Admin Role:**
    *   **Admin Dashboard** (`/admin`) වෙත යොමු කෙරේ.
*   **User/Officer Role:**
    *   **User Dashboard** (`/dashboard`) වෙත යොමු කෙරේ.

## 4 වන අදියර: දෝෂ හැසිරවීම (Error Handling)
Authentication අසාර්ථක වූ විට (වැරදි NIC හෝ Password):
*    පරිශීලකයාව නැවත Login පිටුවට යොමු කෙරේ.
*   දෝෂ පණිවිඩයක් දර්ශනය වේ: *"The provided credentials do not match our records."*
*   පහසුව සඳහා NIC කොටසෙහි කලින් ඇතුළත් කළ අංකය එලෙසම පවතියි.

## සාරාංශය (Summary Matrix)

| Feature | Details |
| :--- | :--- |
| **Primary Key** | NIC Number |
| **Security** | Hashed Passcode Verification |
| **Role Handling** | Automatic Redirection (Admin vs. User) |
| **Security Log** | Automatic Audit Log Entry |
| **Error Feedback** | Standard Invalid Credentials Message |
