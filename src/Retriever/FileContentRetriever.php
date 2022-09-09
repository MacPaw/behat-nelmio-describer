<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Retriever;

class FileContentRetriever
{
    public function __construct(private readonly string $behatTestPath) {
    }

    public function getFileReferenceContent(string $filename, string $anchor): ?string
    {
        $filepath = sprintf('%s/%s', $this->behatTestPath, $filename);

        if (!file_exists($filepath)) {
            return null;
        }

        $content = $this->getFileContent($filepath, $anchor);

        return $content === null
            ? null
            : str_replace("\\\\", "\\", $content);
    }

    private function getFileContent(string $filepath, string $anchor): ?string
    {
        $content = '';

        $lines = file($filepath);
        $totalLinesCount = count($lines);
        $boundary = '"""';

        $anchor = sprintf('#! %s', $anchor);
        $lineNum = 0;

        while ($lineNum < $totalLinesCount && trim($lines[$lineNum]) !== $anchor) {
            $lineNum++;
        }

        if ($lineNum >= $totalLinesCount) {
            return null;
        }

        while ($lineNum < $totalLinesCount && trim($lines[$lineNum]) !== $boundary) {
            $lineNum++;
        }

        $line = $lines[$lineNum];
        $indent = strlen($line) - strlen(ltrim($line));

        while (++$lineNum < $totalLinesCount) {
            $line = rtrim(substr($lines[$lineNum], $indent));

            if ($line === $boundary) {
                break;
            }

            $content .= $line . "\n";
        }

        return $content;
    }
}
