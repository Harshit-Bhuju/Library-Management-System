<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
requireAdmin();

// Process Return
if (isset($_POST['return_book'])) {
    $issue_id = (int)$_POST['issue_id'];
    $book_id = (int)$_POST['book_id'];
    $fine = (float)$_POST['fine'];

    try {
        $pdo->beginTransaction();

        // Update Issue Record
        $pdo->prepare("UPDATE issued_books SET return_date = CURDATE(), status = 'returned', fine_amount = ? WHERE issue_id = ?")->execute([$fine, $issue_id]);

        // Increment Book Copy
        $pdo->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE book_id = ?")->execute([$book_id]);

        $pdo->commit();
        setFlash('success', 'Book returned successfully.');
    } catch (Exception $e) {
        $pdo->rollBack();
        setFlash('error', 'Error: ' . $e->getMessage());
    }
    redirect('admin/return_book.php');
}

// Fetch Active Issues
$sql = "
    SELECT i.*, b.title, b.isbn, u.name as student_name, u.roll_no,
    DATEDIFF(CURDATE(), i.due_date) as days_overdue
    FROM issued_books i
    JOIN books b ON i.book_id = b.book_id
    JOIN users u ON i.user_id = u.user_id
    WHERE i.status != 'returned'
    ORDER BY i.due_date ASC
";
$issues = $pdo->query($sql)->fetchAll();

$pageTitle = 'Return Books';
require_once '../includes/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../includes/sidebar.php'; ?>
    
    <main class="main-content">
        <header class="flex justify-between items-center" style="margin-bottom: 2rem;">
            <h1>Return Books</h1>
            <a href="issue_book.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Issue New</a>
        </header>

        <?php echo getFlash(); ?>

        <div class="card" style="background: var(--card-bg); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--border-color);">
            <div style="margin-bottom: 1rem;">
                <input type="text" id="returnSearch" class="form-control" placeholder="Search by Student or Book..." onkeyup="filterTable('returnTable', 1)">
            </div>

            <div style="overflow-x: auto;">
                <table class="data-table" id="returnTable">
                    <thead>
                        <tr>
                            <th>Issue ID</th>
                            <th>Student</th>
                            <th>Book</th>
                            <th>Issued On</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($issues as $issue): ?>
                        <?php 
                            $isOverdue = $issue['days_overdue'] > 0;
                            $fine = $isOverdue ? ($issue['days_overdue'] * 0.50) : 0; // $0.50 per day
                        ?>
                        <tr>
                            <td>#<?php echo $issue['issue_id']; ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($issue['student_name']); ?></div>
                                <small style="color: var(--text-muted);"><?php echo htmlspecialchars($issue['roll_no']); ?></small>
                            </td>
                            <td>
                                <div><?php echo htmlspecialchars($issue['title']); ?></div>
                                <small style="color: var(--text-muted);"><?php echo htmlspecialchars($issue['isbn']); ?></small>
                            </td>
                            <td><?php echo formatDate($issue['issue_date']); ?></td>
                            <td><?php echo formatDate($issue['due_date']); ?></td>
                            <td>
                                <?php if($isOverdue): ?>
                                    <span style="color: var(--danger); font-weight: 700;">Overdue (<?php echo $issue['days_overdue']; ?> Days)</span>
                                <?php else: ?>
                                    <span style="color: var(--success);">Active</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-primary" onclick="openReturnModal(
                                    '<?php echo $issue['issue_id']; ?>',
                                    '<?php echo $issue['book_id']; ?>',
                                    '<?php echo addslashes($issue['title']); ?>',
                                    '<?php echo $fine; ?>'
                                )" style="padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    Return
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Return Confirmation Modal -->
<div id="returnModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center;">
    <div style="background: var(--card-bg); padding: 2rem; border-radius: 1rem; width: 400px; max-width: 90%; text-align: center;">
        <h2 style="margin-bottom: 1rem;">Confirm Return</h2>
        <p style="margin-bottom: 1rem;">Are you sure you want to return <strong id="modalBookTitle"></strong>?</p>
        
        <div id="fineSection" style="margin-bottom: 1.5rem; padding: 1rem; background: rgba(239, 68, 68, 0.1); border-radius: 0.5rem; color: var(--danger); display: none;">
            <i class="fa-solid fa-triangle-exclamation"></i> Fine Due: $<span id="modalFineAmount">0.00</span>
        </div>

        <form method="POST">
            <input type="hidden" name="return_book" value="1">
            <input type="hidden" name="issue_id" id="modalIssueId">
            <input type="hidden" name="book_id" id="modalBookId">
            <input type="hidden" name="fine" id="modalFineInput">
            
            <div class="flex gap-4 justify-center">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('returnModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm Return</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReturnModal(issueId, bookId, bookTitle, fine) {
        document.getElementById('modalIssueId').value = issueId;
        document.getElementById('modalBookId').value = bookId;
        document.getElementById('modalBookTitle').innerText = bookTitle;
        document.getElementById('modalFineInput').value = fine;
        
        if (parseFloat(fine) > 0) {
            document.getElementById('fineSection').style.display = 'block';
            document.getElementById('modalFineAmount').innerText = parseFloat(fine).toFixed(2);
        } else {
            document.getElementById('fineSection').style.display = 'none';
        }
        
        document.getElementById('returnModal').style.display = 'flex';
    }
    
    // Filter Table
    function filterTable(tableId, colIndex) {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("returnSearch");
        filter = input.value.toUpperCase();
        table = document.getElementById(tableId);
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
             // Check Student Name (1) OR Book Title (2)
            var td1 = tr[i].getElementsByTagName("td")[1];
            var td2 = tr[i].getElementsByTagName("td")[2];
            if (td1 || td2) {
                var txt1 = td1.textContent || td1.innerText;
                var txt2 = td2.textContent || td2.innerText;
                if (txt1.toUpperCase().indexOf(filter) > -1 || txt2.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

<?php require_once '../includes/footer.php'; ?>
