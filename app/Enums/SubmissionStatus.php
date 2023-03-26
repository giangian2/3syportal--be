<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Valid()
 * @method static static DocumentRequired()
 * @method static static SignatureRequired()
 * @method static static DocumentRefused()
 * @method static static DocumentApproved()
 * @method static static InApproval()
 */
final class SubmissionStatus extends Enum
{
    const Valid = "valid";
    const DocumentRequired = "document required";
    const DocumentRefused="rejected";
    const SignatureRequired="signature required";
    const DocumentApproved="approved";
    const InApproval="in approval";
}
