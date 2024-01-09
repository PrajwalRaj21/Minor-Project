#search algorithm

class TrieNode:
    def __init__(self):
        self.children = {}
        self.is_end_of_word = False
        self.failure_link = None


class AhoCorasick:
    def __init__(self, keywords):
        self.root = TrieNode()
        self.build_trie(keywords)
        self.build_failure_links()

    def build_trie(self, keywords):
        for keyword in keywords:
            node = self.root
            for char in keyword:
                node = node.children.setdefault(char, TrieNode())
            node.is_end_of_word = True

    def build_failure_links(self):
        queue = []
        for child in self.root.children.values():
            child.failure_link = self.root
            queue.append(child)

        while queue:
            current_node = queue.pop(0)

            for char, child_node in current_node.children.items():
                queue.append(child_node)

                failure_link_node = current_node.failure_link

                while failure_link_node is not None and char not in failure_link_node.children:
                    failure_link_node = failure_link_node.failure_link

                child_node.failure_link = failure_link_node.children.get(char, self.root)

    def search(self, text):
        current_node = self.root
        results = []

        for i, char in enumerate(text):
            while current_node is not None and char not in current_node.children:
                current_node = current_node.failure_link

            if current_node is None:
                current_node = self.root
                continue

            current_node = current_node.children[char]

            if current_node.is_end_of_word:
                results.append((i - len(keyword) + 1, i))

        return results


keywords = ["car", "rent", "vehicle", "rental"]
text_to_search = "I want to rent a car for my vacation. Where can I find vehicle rental services?"

aho_corasick = AhoCorasick(keywords)
results = aho_corasick.search(text_to_search)

print("Keyword occurrences:")
for start, end in results:
    print(f"{text_to_search[start:end + 1]} found at position {start}")


#there are still a lot to be done such as testing and all.