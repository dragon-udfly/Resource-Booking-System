# Preference & Account Settings: පරිශීලක අත්පොත

## හැඳින්වීම (Introduction)
**Preference** පිටුව මගින් පිවිස සිටින නිලධාරීන්ට (Logged-in officers) තම පුද්ගලික ගිණුම් විස්තර සහ ආරක්ෂක සැකසුම් කළමනාකරණය කිරීමට ඉඩ ලබා දේ. මීට සම්බන්ධතා තොරතුරු යාවත්කාලීන කිරීම (Update) සහ පද්ධතියට ඇතුළු වීමේ මුරපදය (Login password) වෙනස් කිරීම ඇතුළත් වේ.

---

## 1 වන අදියර: Preference පිටුවට පිවිසීම
**URL:** `/preference`

1. **Navigation (ගමන් මග):**
    *   System එකට Log in වන්න.
    *   ප්‍රධාන මෙනුවේ (ඉහළ වම් පස) **Preference** මත ක්ලික් කරන්න.
    *   *සටහන:* මෙය සාමාන්‍යයෙන් අවසර ලත් පරිශීලකයින්ට පමණක් ලබා ගත හැක.

2.  **Interface Overview (අතුරු මුහුණත පිළිබඳ දළ විශ්ලේෂණය):**
    *   **Top Bar:** "Go Back" සහ "Log Out" බොත්තම් අඩංගු වේ.
    *   **User Info:** Log වී සිටින පරිශීලකයාගේ නම සහ තනතුර පෙන්වයි.
    *   **Profile Details:** Email සහ Contact Number යාවත්කාලීන කිරීමේ පෝරමය.
    *   **Change Password:** පිවිසුම් මුරපදය (Passcode) යාවත්කාලීන කිරීමේ පෝරමය.

---

## 2 වන අදියර: Profile Details කළමනාකරණය
**Form Fields:**

1.  **Email Address:**
    *   *වෙනස් කළ හැකිද:* ඔව්
    *   *අවශ්‍යතාවය:* අනිවාර්යයි
    *   *විස්තරය:* පද්ධති දැනුම්දීම් සඳහා ඔබේ නිල විද්‍යුත් තැපැල් ලිපිනය.

2.  **Contact Number:**
    *   *වෙනස් කළ හැකිද:* ඔව්
    *   *අවශ්‍යතාවය:* අනිවාර්යයි
    *   *විස්තරය:* ඔබේ ප්‍රධාන දුරකථන අංකය (ඉලක්කම් 10).

**ක්‍රියාවලිය (Process):**
1.  රශ්ව අවශ්‍ය පරිදි **Email Address** හෝ **Contact Number** වෙනස් කරන්න.
2.  **Update Profile** බොත්තම ක්ලික් කරන්න.
3.  තහවුරු කිරීමේ පණිවිඩයේ (Popup modal) ක්‍රියාව තහවුරු කරන්න ("Yes, Save Changes").
4.  සාර්ථක වූ පසු, තහවුරු කිරීමේ පණිවිඩයක් දිස්වනු ඇත.

---

## 3 වන අදියර: Password (මුරපදය) වෙනස් කිරීම
**Form Fields:**

1.  **New Password:**
    *   *වර්ගය:* Password (සාමාන්‍යයෙන් සැඟවී ඇත)
    *   *අවශ්‍යතාවය:* අනිවාර්යයි
    *   *සීමාවන්:* අවම වශයෙන් අකුරු 4 ක් විය යුතුය.

2.  **Confirm New Password:**
    *   *වර්ගය:* Password
    *   *අවශ්‍යතාවය:* අනිවාර්යයි
    *   *සීමාවන්:* "New Password" සමඟ හරියටම ගැලපිය යුතුය.

**ක්‍රියාවලිය (Process):**
1.  ඔබට අවශ්‍ය නව මුරපදය ඇතුළත් කරන්න.
2.  **Confirm New Password** කොටසේ එම මුරපදයම නැවත ඇතුළත් කරන්න.
3.  **Change Password** බොත්තම ක්ලික් කරන්න.
4.  තහවුරු කිරීමේ පණිවිඩයේ (Popup modal) ක්‍රියාව තහවුරු කරන්න.

---

## 4 වන අදියර: Confirmation සහ Processing (තහවුරු කිරීම සහ සැකසීම)
Profile Update සහ Password Change යන දෙකම පහත ආරක්ෂක පියවර ක්‍රියාත්මක කරයි:

1.  **Confirmation Modal (තහවුරු කිරීමේ පණිවිඩය):**
    *   ඔබගේ අභිප්‍රාය තහවුරු කිරීමට පණිවිඩයක් දිස්වේ (උදා: *"Are you sure you want to update your profile details?"*).
    *   **Yes, Save Changes:** යාවත්කාලීන කිරීම ඉදිරියට ගෙන යයි.
    *   **Cancel:** පණිවිඩය වසා දමයි.

2.  **Processing (සැකසීම):**
    *   පද්ධතිය විසින් ඇතුළත් කළ දත්ත පරීක්ෂා කරයි (උදා: මුරපද ගැලපීම, ඊමේල් ආකෘතිය).
    *   Server එක ඉල්ලීම සකසන අතරතුර "Processing..." යනුවෙන් දර්ශනය වේ.

3.  **Completion (සම්පූර්ණ කිරීම):**
    *   **Success:** සාර්ථක පණිවිඩයක් දිස්වේ (උදා: "Profile details updated successfully.").
    *   **Failure:** දෝෂ පණිවිඩයක් දිස්වේ (උදා: "Passwords do not match").

---

## 5 වන අදියර: System Actions & Audit Logging
ආරක්ෂාව සහ වගවීම සඳහා පද්ධතිය මෙම වෙනස්කම් ස්වයංක්‍රීයව වාර්තා කරයි:

1.  **Profile Update:**
    *   දත්ත ගබඩාවේ (Database) `email` සහ `contact_number` යාවත්කාලීන කරයි.
    *   `modified_datetime` යාවත්කාලීන කරයි.
    *   **Audit Log:** *"User [ID] updated their profile details"* ලෙස සටහන් වේ.

2.  **Password Change:**
    *   නව මුරපදය ගබඩා කිරීමට පෙර එය Encrypt (Hash) කරයි.
    *   `modified_datetime` යාවත්කාලීන කරයි.
    *   **Audit Log:** *"User [ID] changed their passcode"* ලෙස සටහන් වේ.

---

## සාරාංශය (Summary Matrix)

| Feature | Details |
| :--- | :--- |
| **Profile Access** | Email & Contact Number වෙනස් කළ හැක |
| **Password Security** | අවම වශයෙන් අක්ෂර 4යි |
| **Safety** | සියලුම ක්‍රියා සඳහා Confirmation Modals ඇත |
| **Audit Compliance** | Profile සහ Password වෙනස්කම් සඳහා වෙන වෙනම ලොග් සටහන් වේ |
